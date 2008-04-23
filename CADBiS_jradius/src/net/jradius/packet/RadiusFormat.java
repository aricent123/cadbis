/**
 * JRadius - A RADIUS Server Java Adapter
 * Copyright (C) 2004-2005 PicoPoint, B.V.
 * Copyright (c) 2006 David Bird <david@coova.com>
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 2.1 of the License, or (at
 * your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public
 * License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library; if not, write to the Free Software Foundation,
 * Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 */

package net.jradius.packet;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.Iterator;

import net.jradius.log.RadiusLog;
import net.jradius.packet.attribute.AttributeFactory;
import net.jradius.packet.attribute.AttributeList;
import net.jradius.packet.attribute.RadiusAttribute;
import net.jradius.packet.attribute.VSAttribute;
import net.jradius.packet.attribute.value.AttributeValue;

/**
 * Default RadiusPacket/RadiusAttribute format class. This class formats
 * and parses UDP RADIUS Packets. Derived classes implement other formats.
 *
 * @author David Bird
 */
public class RadiusFormat
{
    private static final int HEADER_LENGTH = 2;
    public static final int VSA_HEADER_LENGTH = 8;

    private static final RadiusFormat staticFormat = new RadiusFormat();
    
    /**
     * @return Returns a static instnace of this class
     */
    public static RadiusFormat getInstance()
    {
        return staticFormat;
    }
    
    /**
     * Parses attributes and places them in a RadiusPacket
     * @param packet The RadiusPacket to parse attributes into
     * @param bAttributes The attribute bytes to parse
     */
    public static void setAttributeBytes(RadiusPacket packet, byte[] bAttributes)
    {
        if (bAttributes.length > 0)
        {
            staticFormat.unpackAttributes(
                    packet.getAttributes(), 
                    bAttributes, 
                    bAttributes.length);
        }
    }

    /**
     * Packs a RadiusPacket into a byte array
     * @param packet The RadiusPacket to pack
     * @return Returns the packed RadiusPacket
     */
    public byte[] packPacket(RadiusPacket packet)
    {
        ByteArrayOutputStream out = new ByteArrayOutputStream();
        byte[] attributeBytes = null;
        
        if (packet != null)
        {
            attributeBytes = packAttributeList(packet.getAttributes());
        }
        
        try
        {
            packHeader(out, packet, attributeBytes);
            if (attributeBytes != null) out.write(attributeBytes);
            out.close();
        }
        catch(Exception e)
        {
            e.printStackTrace();
        }
        
        return out.toByteArray();
    }

    /**
     * Packs an AttributeList into a byte array
     * @param attrs The AttributeList to pack
     * @return Returns the packed AttributeList
     */
    public byte[] packAttributeList(AttributeList attrs)
    {
        ByteArrayOutputStream out = new ByteArrayOutputStream();
        
        Iterator iterator = attrs.getAttributeList().iterator();
        while (iterator.hasNext())
        {
        		RadiusAttribute attr = (RadiusAttribute)iterator.next();
        		try
        		{
        			packAttribute(out, attr);
        		}
        		catch (Exception e)
        		{
        			e.printStackTrace();
        		}
        }
        try
        {
            out.close();
        }
        catch(Exception e)
        {
            e.printStackTrace();
        }
        
        return out.toByteArray();
    }

    /**
     * Packs a RadiusAttribute into a DataOutputStream
     * @param out The DataOutputStream to write attibutes to
     * @param a The RadiusAttribute to pack
     * @throws IOException
     */
    public void packAttribute(OutputStream out, RadiusAttribute a) throws IOException
    {
        AttributeValue attributeValue = a.getValue();
        packHeader(out, a);
        attributeValue.getBytes(out);
    }

    /**
     * Packs the RadiusPacket into a DataOutputStream
     * @param out The DataOutputStream to write to
     * @param p The RadiusPacket to pack
     * @param attributeBytes The RadiusPacket attributes
     * @throws IOException
     */
    public void packHeader(OutputStream out, RadiusPacket p, byte[] attributeBytes) throws IOException
    {
        int length = attributeBytes.length + RadiusPacket.RADIUS_HEADER_LENGTH;
        writeUnsignedByte(out, p.getCode());
        writeUnsignedByte(out, p.getIdentifier());
        writeUnsignedShort(out, length);
        out.write(p.getAuthenticator(attributeBytes));
    }
    
    /**
     * Packs a RadiusAttribute header into a DataOutputStream
     * @param out The DataOutputStream to write to
     * @param a The RadiusAttribute to pack
     * @throws IOException
     */
    public void packHeader(OutputStream out, RadiusAttribute a) throws IOException
    {
        if (a instanceof VSAttribute) 
        { 
            packHeader(out, (VSAttribute)a); 
            return; 
        }
        AttributeValue attributeValue = a.getValue();
        writeUnsignedByte(out, (int)a.getType());
        writeUnsignedByte(out, attributeValue.getLength() + HEADER_LENGTH);
    }

    /**
     * Packs a VSAttribute header into a DataOutputStream
     * @param out The DataOutputStream to write to
     * @param a The VSAttribute to pack
     * @throws IOException
     */
    public void packHeader(OutputStream out, VSAttribute a) throws IOException
    {
        AttributeValue attributeValue = a.getValue();
        writeUnsignedByte(out, (int)a.getType());
        writeUnsignedByte(out, attributeValue.getLength() + VSA_HEADER_LENGTH);
        writeUnsignedInt(out, a.getVendorId());
        writeUnsignedByte(out, (int)a.getVsaAttributeType());
        writeUnsignedByte(out, attributeValue.getLength() + 2);
    }
    
    protected class AttributeParseContext
    {
        public int attributeType = 0;
        public int attributeLength = 0;
        public int attributeOp = RadiusAttribute.Operator.EQ;
        public byte[] attributeValue = null;
        public int headerLength = 0;
        public int vendorNumber = -1;
        public int padding = 0;
    }
    
    /**
     * Unpacks RadiusAttributes from a byte array into an AttributeList
     * @param attrs The AttributeList to put unpacked attributes
     * @param bytes The bytes to be unpacked
     * @param bLength The length of the bytes to be unpacked
     */
    public void unpackAttributes(AttributeList attrs, byte[] bytes, int bLength) 
    {
        InputStream attributeInput = new ByteArrayInputStream(bytes);

        try
        {
            for (int pos = 0; pos < bLength; )
            {
                AttributeParseContext ctx = new AttributeParseContext();

                pos += unpackAttributeHeader(attributeInput, ctx);
                
                RadiusAttribute attribute = null;
                ctx.attributeValue = new byte[(int)(ctx.attributeLength - ctx.headerLength)];
                attributeInput.read(ctx.attributeValue, 0, ctx.attributeLength - ctx.headerLength);
                attribute = AttributeFactory.newAttribute(ctx.vendorNumber, ctx.attributeType, ctx.attributeValue, ctx.attributeOp);
                if (attribute == null)
                {
                    RadiusLog.warn("Unknown attribute with type = " + ctx.attributeType);
                }
                else
                {
                    attrs.add(attribute, false);
                }

                if (ctx.padding > 0) 
                { 
                    pos += ctx.padding; 
                    while (ctx.padding-- > 0) 
                    {
                    		readUnsignedByte(attributeInput);
                    }
                }
                
                pos += ctx.attributeLength;
            }
            attributeInput.close();
        }
        catch (IOException e)
        {
            e.printStackTrace();
        }
    }

    /**
     * Unpacks the header of a RadiusAttribute from a DataInputStream
     * @param in The DataInputStream to read from
     * @param ctx The Attribute Parser Context 
     * @return Returns the additional offset length for this header
     * @throws IOException
     */
    protected int unpackAttributeHeader(InputStream in, AttributeParseContext ctx) throws IOException
    {
        ctx.attributeType = readUnsignedByte(in);
        ctx.attributeLength = readUnsignedByte(in);
        ctx.headerLength = 2;
        
        return 0;
    }
    
    public static long readUnsignedInt(InputStream in) throws IOException
    {
        return ((long)readUnsignedShort(in) << 16) | (long)readUnsignedShort(in);
    }
    
    public static int readUnsignedShort(InputStream in) throws IOException
    {
        return (readUnsignedByte(in) << 8) | readUnsignedByte(in);
    }
    
    public static int readUnsignedByte(InputStream in) throws IOException
    {
        return in.read() & 0xFF;
    }
    
    public static void writeUnsignedByte(OutputStream out, int b) throws IOException
    {
        out.write(b);
    }
    
    public static void writeUnsignedShort(OutputStream out, int s) throws IOException
    {
        out.write((s >> 8) & 0xFF);
        out.write(s & 0xFF);
    }
    
    public static void writeUnsignedInt(OutputStream out, long i) throws IOException
    {
        writeUnsignedShort(out, (int)(i >> 16) & 0xFFFF);
        writeUnsignedShort(out, (int)i & 0xFFFF);
    }
}

/**
 * JRadius - A RADIUS Server Java Adapter
 * Copyright (C) 2004-2005 PicoPoint, B.V.
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

package cadbis.jradius;

import net.jradius.dictionary.Attr_AuthType;
import net.jradius.dictionary.Attr_CleartextPassword;
import net.jradius.dictionary.Attr_UserName;
import net.jradius.dictionary.Attr_UserPassword;
import net.jradius.exception.RadiusException;
import net.jradius.handler.PacketHandlerBase;
import net.jradius.log.RadiusLog;
import net.jradius.packet.AccessAccept;
import net.jradius.packet.RadiusPacket;
import net.jradius.packet.attribute.AttributeList;
import net.jradius.server.JRadiusRequest;
import net.jradius.server.JRadiusServer;
import net.jradius.session.JRadiusSession;

import cadbis.db.*;


/**
 * A simple Local Users implementation where users and attributes
 * are defined in the JRadius XML configuration.
 * 
 * @author David Bird
 */
public class CADBiSHandler extends PacketHandlerBase
{	
    public boolean handle(JRadiusRequest jRequest)
    {
        try
        {
            /*
             * Gather some information about the JRadius request
             */
            int type = jRequest.getType();
            AttributeList ci = jRequest.getConfigItems();
            RadiusPacket req = jRequest.getRequestPacket();
            RadiusPacket rep = jRequest.getReplyPacket();

            JRadiusSession session = jRequest.getSession();
            
            /*
             * Find the username in the request packet
             */
            String username = (String)req.getAttributeValue(Attr_UserName.TYPE);
            
            /*
             * See if this is a local user, otherwise we will reject (though, you may
             * want to return "ok" if you have modules configured after jradius in FreeRADIUS)
             */
    	    
    	    
    	    if (username!=null && !username.equals("smecsia"))
    	    {
    	        // Unknown username, so let the RADIUS server sort it out.
    	        RadiusLog.info("Ignoring unknown username: " + username);
    	        return false;
            }
    	    
    	    

            switch (type)
            {
	        	case JRadiusServer.JRADIUS_accounting:
	        	{
	        		String accInfo = "";//session.getJRadiusKey() + " | " + session.getUsername() + " | " +session.getNasIPAddress() + " | " +session.getPassword() + " | " + session.getClientIPAddress();
	        		RadiusLog.info("Accounting!" + accInfo);
	        	}
	        	break;
	        	case JRadiusServer.JRADIUS_authorize:
	        	{
	        		
	        		if (username!=null && !username.equals("smecsia"))
	        		{
	        			jRequest.setReturnValue(JRadiusServer.RLM_MODULE_REJECT);
	        			return false;
	        		}
	        		else
	        		{
		        		String userpwd = "test";
		                ci.add(new Attr_UserPassword(userpwd));      // FreeRADIUS 1.0
		                ci.add(new Attr_CleartextPassword(userpwd)); // FreeRADIUS 2.0
		                ci.add(new Attr_AuthType(Attr_AuthType.MSCHAP)); // Auth through mschap
	        		}
	                
	        	}
	        	break;
            	case JRadiusServer.JRADIUS_post_auth:
            	{
            	    if (rep instanceof AccessAccept)
            	    {
            	        /*
            	         * FreeRADIUS has returned after the authentication checks and the
            	         * user's credentials worked. Since we are now returning an AccessAccept,
            	         * we will the packet with the attributes configured for the user.
            	         */
            	        //rep.addAttributes(u.getAttributeList());
            	        RadiusLog.info("Authentication successful for username: " + username);
            	        
            	    }
            	    else
            	    {
            	        RadiusLog.info("Authentication failed for username: " + username);
            	    }
            	}
            	break;
            }
        }
        catch (RadiusException e)
        {
            e.printStackTrace();
        }
        
        /*
         * Everything worked out well, from the perspective of this module.
         */
        jRequest.setReturnValue(JRadiusServer.RLM_MODULE_UPDATED);
        return false;
    }
}

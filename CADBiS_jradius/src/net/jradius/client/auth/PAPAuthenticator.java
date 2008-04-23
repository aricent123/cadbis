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

package net.jradius.client.auth;

import net.jradius.exception.RadiusException;
import net.jradius.packet.RadiusPacket;
import net.jradius.util.RadiusUtils;


/**
 * PAP (default) Authentication.
 * 
 * @author David Bird
 */
public class PAPAuthenticator extends RadiusAuthenticator 
{
    public static final String NAME = "pap";

    public String getAuthName()
    {
        return NAME;
    }
    
    public void processRequest(RadiusPacket p) throws RadiusException
    {
        password.setValue(RadiusUtils.encodePapPassword(
	            client.getMD(), 
	            password.getValue().getBytes(), 
	            // Create an authenticator (AccessRequest just needs shared secret)
	            p.createAuthenticator(null), 
	            client.getSharedSecret()));
    }
}

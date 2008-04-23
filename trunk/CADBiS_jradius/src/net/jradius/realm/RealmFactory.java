/**
 * JRadius - A RADIUS Server Java Adapter
 * Copyright (C) 2004-2006 PicoPoint, B.V.
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

package net.jradius.realm;

import java.util.Collection;

import net.jradius.exception.RadiusException;
import net.jradius.server.config.XMLConfiguration;

import org.apache.commons.configuration.HierarchicalConfiguration;

/**
 * @author David Bird
 */
public interface RealmFactory
{
    public JRadiusRealm getRealm(String realmName) throws RadiusException;
    public Collection getRealms() throws RadiusException;
    public void setConfig(XMLConfiguration config, HierarchicalConfiguration.Node root);
}

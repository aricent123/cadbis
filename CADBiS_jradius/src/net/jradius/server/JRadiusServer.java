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

package net.jradius.server;

import java.lang.reflect.InvocationTargetException;
import java.util.Collection;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.LinkedBlockingQueue;

import net.jradius.handler.chain.JRCommand;
import net.jradius.log.RadiusLog;
import net.jradius.packet.attribute.AttributeDictionary;
import net.jradius.packet.attribute.AttributeFactory;
import net.jradius.server.config.Configuration;
import net.jradius.server.config.DictionaryConfigurationItem;
import net.jradius.server.config.ListenerConfigurationItem;
import net.jradius.session.JRadiusSessionManager;

/**
 * Constants used in the server. This is currently too FreeRADIUS specific
 * and that will change.
 *
 * @author David Bird
 */
public class JRadiusServer
{
    /**
     * TODO:
     * The following are taken from FreeRADIUS. JRadius should, however,
     * define its own (non server specific) values here! 
     */
    public static final int JRADIUS_authenticate = 1;
    public static final int JRADIUS_authorize    = 2;
    public static final int JRADIUS_preacct      = 3;
    public static final int JRADIUS_accounting   = 4;
    public static final int JRADIUS_checksimul   = 5;
    public static final int JRADIUS_pre_proxy    = 6;
    public static final int JRADIUS_post_proxy   = 7;
    public static final int JRADIUS_post_auth    = 8;
    public static final int JRADIUS_max_request_type = 8; /* the highest numbered request type */
    
    public static final int RLM_MODULE_REJECT    = 0;   /* immediately reject the request */
    public static final int RLM_MODULE_FAIL      = 1;   /* module failed, don't reply */
    public static final int RLM_MODULE_OK        = 2;   /* the module is OK, continue */
    public static final int RLM_MODULE_HANDLED   = 3;   /* the module handled the request, so stop. */
    public static final int RLM_MODULE_INVALID   = 4;   /* the module considers the request invalid. */
    public static final int RLM_MODULE_USERLOCK  = 5;   /* reject the request (user is locked out) */
    public static final int RLM_MODULE_NOTFOUND  = 6;   /* user not found */
    public static final int RLM_MODULE_NOOP      = 7;   /* module succeeded without doing anything */
    public static final int RLM_MODULE_UPDATED   = 8;   /* OK (pairs modified) */
    public static final int RLM_MODULE_NUMCODES  = 9;   /* How many return codes there are */

    private Collection processors;
    private Collection listeners;
    
    private static final EventDispatcher eventDispatcher = new EventDispatcher();
    
    /**
     * Initializes a new JRadiusServer. The constructor calls initializeServer(),
     * the initialization method that reads the configuration file and sets up
     * processors and listeners.
     * @throws SecurityException
     * @throws IllegalArgumentException
     * @throws ClassNotFoundException
     * @throws NoSuchMethodException
     * @throws InstantiationException
     * @throws IllegalAccessException
     * @throws InvocationTargetException
     */
    public JRadiusServer() throws Exception
    {
        processors = new LinkedList();
        listeners = new LinkedList();

        initializeServer();
    }
    
    /**
     * Start the JRadiusServer. Make sure the server is
     * initialized first by calling initializeServer()
     */
    public void start()
    {
        RadiusLog.info("Starting Event Dispatcher...");
        eventDispatcher.start();
        
        RadiusLog.info("Starting Processors...");
        for (Iterator i = processors.iterator(); i.hasNext();)
        {
            Processor processor = (Processor) i.next();
            processor.start();
            RadiusLog.info("  Started processor " + processor.getName());
        }
        RadiusLog.info("Processors succesfully started.");
        
        RadiusLog.info("Starting Listeners...");
        for (Iterator i = listeners.iterator(); i.hasNext();)
        {
            Listener listener = (Listener) i.next();
            listener.start();
            RadiusLog.info("  Started listener " + listener.getName());
        }
        RadiusLog.info("Listeners succesfully started.");
    }
    
    public void stop()
    {
        for (Iterator i = processors.iterator(); i.hasNext();)
        {
            Processor processor = (Processor) i.next();
            processor.setActive(false);
            try { processor.join(); } catch (Exception e) { }
            RadiusLog.info("Stopping processor " + processor.getName());
        }

        for (Iterator i = listeners.iterator(); i.hasNext();)
        {
            Listener listener = (Listener) i.next();
            listener.setActive(false);
            try { listener.join(); } catch (Exception e) { }
            RadiusLog.info("Stopping listener " + listener.getName());
        }

        JRadiusSessionManager.shutdownManagers();
        eventDispatcher.interrupt();
    }

    
    /**
     * Read the configuration and initialize the JRadiusServer
     * @throws SecurityException
     * @throws IllegalArgumentException
     * @throws ClassNotFoundException
     * @throws NoSuchMethodException
     * @throws InstantiationException
     * @throws IllegalAccessException
     * @throws InvocationTargetException
     */
    private void initializeServer() throws Exception
    {
        RadiusLog.info("Initializing JRadius Server....");
        for (Iterator i = Configuration.getDictionaryConfigs().iterator(); i.hasNext();)
        {
            DictionaryConfigurationItem dictionaryConfig = (DictionaryConfigurationItem) i.next();
            RadiusLog.info("  Loading dictionary: " + dictionaryConfig.getClassName());
            AttributeFactory.loadAttributeDictionary((AttributeDictionary)Configuration.getBean(dictionaryConfig.getClassName()));
        }
        for (Iterator i = Configuration.getListenerConfigs().iterator(); i.hasNext();)
        {
            ListenerConfigurationItem listenerConfig = (ListenerConfigurationItem) i.next();

            LinkedBlockingQueue queue = new LinkedBlockingQueue();
            createListenerWithConfigAndQueue(listenerConfig, queue);
            createProcessorsWithConfigAndQueue(listenerConfig, queue);
        }
        RadiusLog.info("JRadius Server succesfully Initialized.");
    }

    
    private void createProcessorsWithConfigAndQueue(ListenerConfigurationItem listenerConfig, BlockingQueue queue) throws Exception
    {
        for (int j = 0; j < listenerConfig.getNumberOfThreads(); j++)
        {
            Processor processor = newProcessorForName(listenerConfig.getProcessorClassName());
            processor.setRequestQueue(queue);
            RadiusLog.info("    Created processor " + processor.getName());
            setPacketHandlersForProcessor(listenerConfig, processor);
            setEventHandlersForProcessor(listenerConfig, eventDispatcher);
            processor.setEventDispatcher(eventDispatcher);
            processors.add(processor);
        }
    }

    private void setPacketHandlersForProcessor(ListenerConfigurationItem cfg, Processor processor)
    {
        Collection packetHandlers = cfg.getRequestHandlers();
        if (packetHandlers == null)
        {
            RadiusLog.debug("No packet handlers are configured, maybe using chains instead.");
            return;
        }
        for (Iterator l = packetHandlers.iterator(); l.hasNext();)
        {
            JRCommand handler = (JRCommand)l.next();
            RadiusLog.info("      Packet handler " + handler.getClass().getName());
        }
        processor.setRequestHandlers(packetHandlers);
    }
    
    private void setEventHandlersForProcessor(ListenerConfigurationItem cfg, EventDispatcher dispatcher)
    {
        Collection eventHandlers = cfg.getEventHandlers();
        if (eventHandlers == null)
        {
            return;
        }
        for (Iterator l = eventHandlers.iterator(); l.hasNext();)
        {
            JRCommand handler = (JRCommand)l.next();
            RadiusLog.info("      Event handler " + handler.getClass().getName());
        }
        dispatcher.setEventHandlers(eventHandlers);
    }
    
    private void createListenerWithConfigAndQueue(ListenerConfigurationItem listenerConfig, BlockingQueue queue) throws Exception
    {
        Listener listener = newListenerWithConfig(listenerConfig);
        listener.setRequestQueue(queue);
        listeners.add(listener);
        RadiusLog.info("  Created listener " + listener.getName());
    }
    
    private Listener newListenerWithConfig(ListenerConfigurationItem cfg) throws Exception
    {
        Listener listener = (Listener) Configuration.getBean(cfg.getClassName());
        listener.setConfiguration(cfg);
        return listener;
    }
    
    
    private Processor newProcessorForName(String className) throws Exception
    {
        Processor processor = (Processor) Configuration.getBean(className);
        return processor;
    }
    
    public static String resultCodeToString(int resultCode)
    {
        switch(resultCode)
        {
            case RLM_MODULE_REJECT  : return "REJECT";
            case RLM_MODULE_FAIL    : return "FAIL";
            case RLM_MODULE_OK      : return "OK";
            case RLM_MODULE_HANDLED : return "HANDLED";
            case RLM_MODULE_INVALID : return "INVALID";
            case RLM_MODULE_USERLOCK: return "USERLOCK";
            case RLM_MODULE_NOTFOUND: return "NOTFOUND";
            case RLM_MODULE_NOOP    : return "NOOP";
            case RLM_MODULE_UPDATED : return "UPDATED";
            case RLM_MODULE_NUMCODES: return "NUMCODES";
            default:                  return "UNKNOWN";
        }
    }
    
    public static EventDispatcher getEventDispatcher()
    {
        return eventDispatcher;
    }
}

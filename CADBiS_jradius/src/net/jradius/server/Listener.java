/**
 * JRadius - A RADIUS Server Java Adapter
 * Copyright (C) 2004-2006 PicoPoint, B.V.
 * Copyright (c) 2006-2007 David Bird <david@coova.com>
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

import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.InetAddress;
import java.net.InetSocketAddress;
import java.net.ServerSocket;
import java.net.Socket;
import java.security.KeyManagementException;
import java.security.KeyStore;
import java.security.KeyStoreException;
import java.security.NoSuchAlgorithmException;
import java.security.Security;
import java.security.UnrecoverableKeyException;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.concurrent.BlockingQueue;

import javax.net.ServerSocketFactory;
import javax.net.ssl.KeyManager;
import javax.net.ssl.KeyManagerFactory;
import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;
import javax.net.ssl.TrustManagerFactory;
import javax.net.ssl.X509TrustManager;

import net.jradius.exception.RadiusException;
import net.jradius.log.RadiusLog;
import net.jradius.server.config.ListenerConfigurationItem;

/**
 * The base abstract class of all Listeners
 * 
 * @author Gert Jan Verhoog
 * @author David Bird
 */
public abstract class Listener extends JRadiusThread
{
    private boolean active = false;
    protected ListenerConfigurationItem config;
    protected BlockingQueue queue;
    
    private int port = 1814;
    private String host = "localhost";
    private int backlog = 1024;
    private boolean usingSSL = false;
    private boolean keepAlive;
    private ServerSocket serverSocket;
    
    private List keepAliveListeners = new LinkedList();

    public abstract JRadiusEvent parseRequest(InputStream inputStream) throws IOException, RadiusException;

    public void setConfiguration(ListenerConfigurationItem cfg) 
        throws  KeyStoreException, NoSuchAlgorithmException, CertificateException, 
                UnrecoverableKeyException, KeyManagementException, IOException
    {
        setConfiguration(cfg, false);
    }

    public void setConfiguration(ListenerConfigurationItem cfg, boolean noKeepAlive) 
        throws  KeyStoreException, NoSuchAlgorithmException, CertificateException, 
                UnrecoverableKeyException, KeyManagementException, IOException
    {
        keepAlive = !noKeepAlive;
        config = cfg;
        
        Map props = config.getProperties();
        
        
        String s = (String) props.get("port");
        if (s != null) port = new Integer(s).intValue();
        s = (String) props.get("host");
        if (s != null) host = new String(s);
        
        s = (String) props.get("backlog");
        if (s != null) backlog = new Integer(s).intValue();
        
        if (keepAlive) 
        {
            s = (String) props.get("keepAlive");
            if (s != null) keepAlive = new Boolean(s).booleanValue();
        }

        String useSSL = (String) props.get("useSSL");
        String trustAll = (String) props.get("trustAll");

        if ("true".equalsIgnoreCase(useSSL))
        {
            Security.addProvider(new com.sun.net.ssl.internal.ssl.Provider());
            KeyManager[] keyManagers = null;
            TrustManager[] trustManagers = null;
            
            String keystore         = (String) props.get("keyStore");
            String keystoreType     = (String) props.get("keyStoreType");
            String keystorePassword = (String) props.get("keyStorePassword");
            String keyPassword      = (String) props.get("keyPassword");
            
            if (keystore != null)
            {
                if (keystoreType == null) keystoreType = "pkcs12";

                KeyStore ks = KeyStore.getInstance(keystoreType);
                ks.load(new FileInputStream(keystore), keystorePassword == null ? null : keystorePassword.toCharArray());

                KeyManagerFactory kmf = KeyManagerFactory.getInstance("SunX509");
                kmf.init(ks, keyPassword == null ? null : keyPassword.toCharArray());
                keyManagers = kmf.getKeyManagers();
            }

            if ("true".equalsIgnoreCase(trustAll))
            {
                trustManagers = new TrustManager[]{ new X509TrustManager()
                        {
                            public void checkClientTrusted(X509Certificate[] chain, String authType) { }
                            public void checkServerTrusted(X509Certificate[] chain, String authType) { }
                            public X509Certificate[] getAcceptedIssuers() { return new X509Certificate[0]; }
                        }};
            }
            else
            {
                keystore         = (String) props.get("caStore");
                keystoreType     = (String) props.get("caStoreType");
                keystorePassword = (String) props.get("caStorePassword");

                if (keystore != null)
                {
                    if (keystoreType == null) keystoreType = "pkcs12";

                    KeyStore caKeys = KeyStore.getInstance(keystoreType);
                    caKeys.load(new FileInputStream(keystore), keystorePassword == null ? null : keystorePassword.toCharArray());
                    TrustManagerFactory tmf = TrustManagerFactory.getInstance("SunX509");
                    tmf.init(caKeys);
                    trustManagers = tmf.getTrustManagers();
                }
            }

            SSLContext sslContext = SSLContext.getInstance("SSLv3");
            sslContext.init(keyManagers, trustManagers, null);
            
            ServerSocketFactory socketFactory = sslContext.getServerSocketFactory();
            serverSocket = socketFactory.createServerSocket(port, backlog);
            usingSSL = true;
        }
        else
        {
            serverSocket = new ServerSocket(port, backlog);
        }
        if(host.length()>0 && host!=null){
        	serverSocket.close();
        	serverSocket = new ServerSocket(port,backlog,InetAddress.getByName(host));
        }
        
        serverSocket.setReuseAddress(true);
        setActive(serverSocket != null);
    }
    
    /**
     * Sets the request queue for this listener
     * 
     * @param q the RequestQueue;
     */
    public void setRequestQueue(BlockingQueue q)
    {
        queue = q;
    }

    /**
     * Sets the listeners ConfigurationItem
     * @param cfg a configuration item
     */
    public void setListenerConfigurationItem(ListenerConfigurationItem cfg)
    {
        config = cfg;
        this.setName(config.getName());
    }
    
    /**
     * Listen for one object and place it on the request queue
     */
    public void listen() throws IOException, InterruptedException, RadiusException
    {
        RadiusLog.debug("Listening on socket...");
        Socket socket = serverSocket.accept();
        if (keepAlive)
        {
            KeepAliveListener keepAliveListener = new KeepAliveListener(socket, this, queue);
            keepAliveListener.start();

            synchronized (keepAliveListeners)
            {
                keepAliveListeners.add(keepAliveListener);
            }
        }
        else
        {
            queue.put(new ListenerRequest(socket, this, false));
        }
    }
    
    public void deadKeepAliveListener(KeepAliveListener keepAliveListener)
    {
    }

    public boolean isActive()
    {
        return active;
    }

    public void setActive(boolean active)
    {
        this.active = active;
        if (!active)
        {
            for (Iterator i = keepAliveListeners.iterator(); i.hasNext(); )
            {
                try { ((KeepAliveListener)i.next()).shutdown(); }
                catch (Exception e) { e.printStackTrace(); }
            }
            for (Iterator i = keepAliveListeners.iterator(); i.hasNext(); )
            {
                try { ((KeepAliveListener)i.next()).interrupt(); }
                catch (Exception e) { e.printStackTrace(); }
            }
            keepAliveListeners.clear();
            try { serverSocket.close(); }
            catch (Exception e) { e.printStackTrace(); }
            interrupt();
        }
    }
    
    /**
     * The thread's run method repeatedly calls listen()
     */
    public void run()
    {
        while (isActive())
        {
            try
            {
                Thread.yield();
                listen();
            }
            catch (Throwable e)
            {
                System.err.println("The Listener's listen() method threw an exception: " + e);
                RadiusLog.error(e.getMessage());
                e.printStackTrace();
            }
        }

        RadiusLog.error("Listener: " + this.getClass().getName() + " exiting (not active)");
    }

    public boolean isUsingSSL() 
    {
        return usingSSL;
    }

    public boolean isKeepAlive()
    {
        return keepAlive;
    }

    public void setBacklog(int backlog)
    {
        this.backlog = backlog;
    }

    public void setKeepAlive(boolean keepAlive)
    {
        this.keepAlive = keepAlive;
    }

    public void setPort(int port)
    {
        this.port = port;
    }

    public void setUsingSSL(boolean usingSSL)
    {
        this.usingSSL = usingSSL;
    }
}

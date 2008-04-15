/*
 *  Licensed to the Apache Software Foundation (ASF) under one
 *  or more contributor license agreements.  See the NOTICE file
 *  distributed with this work for additional information
 *  regarding copyright ownership.  The ASF licenses this file
 *  to you under the Apache License, Version 2.0 (the
 *  "License"); you may not use this file except in compliance
 *  with the License.  You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing,
 *  software distributed under the License is distributed on an
 *  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 *  KIND, either express or implied.  See the License for the
 *  specific language governing permissions and limitations
 *  under the License.
 *
 */
package cadbis.proxy;

import org.apache.mina.common.IoSession;
import org.apache.mina.common.ReadFuture;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

/**
 * Handles the server to proxy part of the proxied connection.
 *
 * @author The Apache MINA Project (dev@mina.apache.org)
 * @version $Rev$, $Date$
 *
 */
public class ServerToProxyIoHandler extends AbstractProxyIoHandler {
	private final Logger logger = LoggerFactory.getLogger(getClass());
	
	@Override
	public void sessionCreated(IoSession session) throws Exception {
		String rmAddr = session.getRemoteAddress().toString();
		String lcAddr = session.getLocalAddress().toString();
		logger.info("Connection from " +lcAddr+" to "+rmAddr+", session started \r\n");
	}

	@Override
	public void messageSent(IoSession session, Object message) throws Exception {
		String sMsg = message.toString();
		String rmAddr = session.getRemoteAddress().toString();
		String lcAddr = session.getLocalAddress().toString();
		logger.info("Connection from " +lcAddr+" to "+rmAddr+", sent "+sMsg.length()+": \r\n");
		logger.info(sMsg);
	}

	@Override
	public void messageReceived(IoSession session, Object message)
			throws Exception {
		String sMsg = message.toString();
		String rmAddr = session.getRemoteAddress().toString();
		String lcAddr = session.getLocalAddress().toString();
		logger.info("Connection from " +lcAddr+" to "+rmAddr+", recieved "+sMsg.length()+": \r\n");
		logger.info(sMsg);
	}
	
}

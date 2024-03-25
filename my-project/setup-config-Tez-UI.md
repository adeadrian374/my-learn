# Install TEZ UI di cluster YAVA 3.1 ROCKY 8.8

# Environment

- YAVA 3.1 

- Rocky 8.8

- Tez 0.10.1

- Hive 3.1.2

- Yarn 3.3.1

- Kerberos

- SSL

# Step

1. Download package tez-ui dari official repository apache, pastikan versi dari package tez-ui sesuai dengan versi dari service Tez yang terinstall di Ambari

```
wget https://repository.apache.org/content/repositories/releases/org/apache/tez/tez-ui/0.10.1/tez-ui-0.10.1.war
```

2. Extract file package tez-ui, pastikan ketika extract file berada di path /var/www/html/tez-ui dari node tez

```
unzip tez-ui-0.10.1.war
```
3. Kemudian masuk ke path /var/www/html/tez-ui/config/ dan masuk ke file `config.js`

4. Config yang ada didalam file config.js seperti berikut;

```
//timeline: "http://<localhost>:8188",
//rm: "http://<localhost>:8088",
//rmProxy: "http://<localhost>:8088",
```
Untuk mendapatkan value dari timeline, rm, & rmProxy bisa didapatkan dari property config yang ada dari Yarn

```
yarn.timeline-service.webapp.address
yarn.timeline-service.webapp.https.address -> using enable ssl

yarn.resourcemanager.webapp.address
yarn.resourcemanager.webapp.https.address -> using enable ssl
```
note :

- dari config tersebut, perlu diperhatikan jika cluster sudah enable ssl maka value yang diambil adalah yang https untuk `config.js`

- apabila cluster belum ssl, bisa menggunakan http untuk config yang dari `config.js`

contoh config dari file `config.js` bila menggunakan ssl :

```
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

ENV = {
  hosts: {
    /*
     * Timeline Server Address:
     * By default TEZ UI looks for timeline server at http://localhost:8188, uncomment and change
     * the following value for pointing to a different address.
     */
    timeline: "https://yava31el8-retest01.labs247.com:8190",
    //timeline: "http://yava31el8-retest01.labs247.com:8188",
    /*
     * Resource Manager Address:
     * By default RM REST APIs are expected to be at http://localhost:8088, uncomment and change
     * the following value to point to a different address.
     */
    rm: "https://yava31el8-retest01.labs247.com:8090",
    //rm: "http://yava31el8-retest01.labs247.com:8088",
    /*
     * Resource Manager Web Proxy Address:
     * Optional - By default, value configured as RM host will be taken as proxy address
     * Use this configuration when RM web proxy is configured at a different address than RM.
     */
    rmProxy: "https://yava31el8-retest01.labs247.com:8090",
    //rmProxy: "http://yava31el8-retest01.labs247.com:8088",
  },

  /*
   * Time Zone in which dates are displayed in the UI:
   * If not set, local time zone will be used.
   * Refer http://momentjs.com/timezone/docs/ for valid entries.
   */
  //timeZone: "UTC",

  /*
   * yarnProtocol:
   * If specified, this protocol would be used to construct node manager log links.
   * Possible values: http, https
   * Default value: If not specified, protocol of hosts.rm will be used
   */
  //yarnProtocol: "<value>",
};
```

Jika sudah save untuk config yang ada di dalam `config.js`

5. Perlu melakukan config dari sisi service Tez 

```
[tez-site]

tez.history.logging.service.class=org.apache.tez.dag.history.logging.ats.ATSHistoryLoggingService
tez.tez-ui.history-url.base=http://<hostname-node-config-tez-ui>/tez-ui/
tez.am.tez-ui.history-url.template=__HISTORY_URL_BASE__?viewPath=/#/tez-app/__APPLICATION_ID__
yarn.timeline-service.enabled=true
```

6. Config dari sisi service Yarn

```
[yarn-site]
    
yarn.timeline-service.https-cross-origin.enabled=true
yarn.timeline-service.https-cross-origin.allowed-origins = *
yarn.timeline-service.hostname =<hostname-timeline-server>(FQDN)

[timeline server]

yarn.timeline-service.enabled=true


[resource manager]

yarn.timeline-service.enabled=true
yarn.admin.acl=yarn
```

7. Config dari sisi service Hive

```
[hive-env]

hive_timeline_logging_enabled=true


[General]

hive.exec.failure.hooks=org.apache.hadoop.hive.ql.hooks.ATSHook
hive.exec.post.hooks=org.apache.hadoop.hive.ql.hooks.ATSHook
hive.exec.pre.hooks=org.apache.hadoop.hive.ql.hooks.ATSHook
```

8. Jika sudah save & restart services dan coba akses tez-ui `http://<hostname-node-config-tez-ui>/tez-ui/`
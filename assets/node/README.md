![easyRTC](./api/img/easyrtc.png "easyRTC")
easyRTC
=======

**A bundle of Open Source WebRTC joy!**

Priologic's easyRTC beta, a bundle of Open Source WebRTC joy, incorporates an easyRTC server install and client API, and working, HTML5 and Javascript, application source code under a BSD 2 license.


Features
--------
 * Install easyRTC's WebRTC Server Kit on your own Linux, Windows, or Mac server in minutes not days.
 * Use our easyRTC API and sample application code to build and deploy your WebRTC app in hours not weeks.
 * easyRTC is completely free and open source under a BSD 2 license. No usage costs or other hidden fees.


Documentation
-------------
 * [Install instructions for Ubuntu, Windows, and Mac](./docs/easyrtc_installing.md)
 * [Configuration options](./docs/easyrtc_configuration.md)
 * [Client API documentation](http://htmlpreview.github.io/?https://raw.github.com/priologic/easyrtc/master/docs/easyRTC.html)
 * [Client API tutorial](./docs/easyrtc_client_tutorial.md)
 * [Frequently asked questions](./docs/easyrtc_faq.md)
 * [Upcoming features](./docs/easyrtc_upcoming_features.md)


Installation In A Nutshell
--------------------------
 1. Install [Node.js](http://nodejs.org)
 2. Download and uncompress easyRTC in the folder of your choice
 3. Run `npm install` from the installation folder to install dependant packages
 4. Start easyRTC by running `node server.js`
 5. Browse the examples using a WebRTC enabled browser. *(defaults to port `8080`)*

Step by step instructions including additional setup options can be found in [/docs/easyrtc_installing.md](./docs/easyrtc_installing.md)


Folder Structure
----------------

* / (root)
  * Contains the main file (server.js) and the configuration file (config.js)
  * Licenses and package information
* /demos/
  * easyRTC live demos and example code
* /docs/
  * Documentation for using the API and running the server
* /lib/
  * Required libraries
* /logs/
  * Default location of file logs (disabled by default)
* /node_modules/
  * Required node.js modules
  * This folder will be created during the install
* /static/
  * Where you can put your own WebRTC application.
  * The easyrtc.js file is located in /static/js/


Included Demos
--------------

easyRTC comes with a number of demo's which work immediatly after installation.

 * Video and/or Audio connections
 * Multi-party video chat
 * Text Messaging with or without Data Channels
 * Screen and tab sharing


Links for help and information
------------------------------

* The easyRTC website is at:
  * [http://www.easyrtc.com/](http://www.easyrtc.com/)
* Use our support forum is at:
  * [https://groups.google.com/forum/#!forum/easyrtc](https://groups.google.com/forum/#!forum/easyrtc)
* Live demo site:
  * [http://demo.easyrtc.com/](http://demo.easyrtc.com/)
* Bugs and requests can be filed on our github page or on the forum:
  * [https://github.com/priologic/easyrtc/issues](https://github.com/priologic/easyrtc/issues)
* Our YouTube channel has live demo's:
  * [http://www.youtube.com/user/priologic](http://www.youtube.com/user/priologic)
  * [![Installing easyRTC Using Git](http://img.youtube.com/vi/Nq042zJ_em4/0.jpg)](http://www.youtube.com/watch?v=Nq042zJ_em4)


License
-------

Copyright (c) 2013, Priologic Software Inc.
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
POSSIBILITY OF SUCH DAMAGE.
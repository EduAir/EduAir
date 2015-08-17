//
//Copyright (c) 2013, Priologic Software Inc.
//All rights reserved.
//
//Redistribution and use in source and binary forms, with or without
//modification, are permitted provided that the following conditions are met:
//
//    * Redistributions of source code must retain the above copyright notice,
//      this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above copyright
//      notice, this list of conditions and the following disclaimer in the
//      documentation and/or other materials provided with the distribution.
//
//THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
//AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
//IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
//ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
//LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
//CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
//SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
//INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
//CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
//ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
//POSSIBILITY OF SUCH DAMAGE.
//
var selfEasyrtcid = "";

function addToConversation(who, content) {
    // Escape html special characters, then add linefeeds.
    content = content.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    content = content.replace(/\n/g, '<br />');
    document.getElementById('conversation').innerHTML += 
    "<b>" + who + ":</b>&nbsp;" + content + "<br />";
}


function connect() {
    easyRTC.setDataListener(addToConversation);
    easyRTC.setLoggedInListener(convertListToButtons);
    easyRTC.connect("im", loginSuccess, loginFailure);
}


function convertListToButtons (data) {
    otherClientDiv = document.getElementById('otherClients');
    while (otherClientDiv.hasChildNodes()) {
        otherClientDiv.removeChild(otherClientDiv.lastChild);
    }
    
    for(var i in data) {        
        var button = document.createElement('button');
        button.onclick = function(easyrtcid) {        
            return function() {
                sendStuffWS(easyrtcid);
            }
        }(i);        
        var label = document.createTextNode("Send to " + easyRTC.idToName(i));
        button.appendChild(label);
                
        otherClientDiv.appendChild(button);        
    }
    if( !otherClientDiv.hasChildNodes() ) {
        otherClientDiv.innerHTML = "<em>Nobody else logged in to talk to...</em>";
    }
}


function sendStuffWS(otherEasyrtcid) {    
    var text = document.getElementById('sendMessageText').value;    
    if(text.replace(/\s/g, "").length == 0) { // Don't send just whitespace
        return;
    }
    
    easyRTC.sendDataWS(otherEasyrtcid, text);
    addToConversation("Me", text);
    document.getElementById('sendMessageText').value = "";        
}


function loginSuccess(easyRTCId) {
    selfEasyrtcid = easyRTCId;
    document.getElementById("iam").innerHTML = "I am " + easyRTCId;
}


function loginFailure(message) {
    easyRTC.showError("LOGIN-FAILURE", message);
}
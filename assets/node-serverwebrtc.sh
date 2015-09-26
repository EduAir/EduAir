#! /bin/sh
# /etc/init.d/node-serverwebrtc
 
### BEGIN INIT INFO
# Provides:          node-serverwebrtc
# Required-Start:    $remote_fs $syslog
# Required-Stop:     $remote_fs $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
### END INIT INFO
 
# change this to wherever your node app lives # 
path_to_node_app=/var/www/assets/node/node_modules/signal-master/server.js 
 
# Carry out specific functions when asked to by the system
case "$1" in
  start)
    echo "* starting node-serverwebrtc * "
    echo "* starting node-serverwebrtc * [`date`]" >> /var/log/node-serverwebrtc.log
    /usr/local/bin/node $path_to_node_app >> /var/log/node-serverwebrtc.log 2>&1&
    ;;
  stop)
    echo "* stopping node-serverwebrtc * "
    echo "* stopping node-serverwebrtc * [`date`]" >> /var/log/node-serverwebrtc.log
    killall /usr/local/bin/node
    ;;
  *)
    echo "Usage: /etc/init.d/node-serverwebrtc {start|stop}"
    exit 1
    ;;
esac
 
exit 0

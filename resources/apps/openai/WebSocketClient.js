const WebSocketClient = function () {
    this.ws = null;
    this.timer = null;
    this.interval = null;
    this.reconnect = true;

    this.open = () => {
        let that = this;
        this.ws = new WebSocket('wss://ai.songdewei.com:39600');

        this.ws.onopen = function (e) {
            console.log("Connected to WebSocket server.");
            that.interval = setInterval(() => {
                that.ws.send('ping');
            }, 50000);
            that.onopen(e);
            that.readyState = that.ws.readyState;
        };

        this.ws.onmessage = function (e) {
            console.log(e.data);
            that.onmessage(e);
        }

        this.ws.onclose = function (e) {
            console.log("Disconnected");
            clearInterval(that.interval);
            if (that.reconnect) {
                clearTimeout(that.timer);
                that.timer = setTimeout(that.open, 2000);
                that.onclose(e);
            }
        };

        this.ws.onerror = function (e) {
            console.log('Error occured: ', e.code);
            that.onerror(e);
        };
    }

    this.onopen = (e) => {

    }

    this.onmessage = (e) => {

    }

    this.onclose = (e) => {

    }

    this.onerror = (e) => {

    }

    this.send = (message) => {
        this.ws.send(message);
    }

    this.close = () => {
        this.reconnect = false;
        this.ws.close();
    }

    this.isConnected = () => {
        return this.ws.readyState === this.ws.OPEN;
    }

    this.open();
}

export default WebSocketClient;

<template>
    <div class="chat-app">
        <div class="chat-sidebar"></div>
        <div class="chat-main">
            <div class="chat-main-frame">
                <div>
                    <div class="chat-message" v-for="(message,index) in dataList" :key="index">
                        <div class="text-base">
                            <div class="avatar">
                                <img src="/images/common/avatar_default.png">
                            </div>
                            <div class="message-text">{{ message.text }}</div>
                        </div>
                    </div>
                </div>
                <div class="input-bar">
                    <div class="input-content">
                        <div class="input-box">
                            <div class="flex-fill">
                                <input class="text-input" placeholder="请输入您想问的" v-model="prompt">
                            </div>
                            <button class="button-send" @click="onSend">发送</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import WebSocketClient from "./WebSocketClient";

export default {
    name: "ChatApp",
    data() {
        return {
            prompt: '',
            dataList: []
        }
    },
    methods: {
        onSend() {
            this.dataList.push({text: this.prompt});
        }
    },
    mounted() {
        let wsClient = new WebSocketClient();
        wsClient.onmessage = e => {
            if (e.data !== '[DONE]') {
                let json = JSON.parse(e.data);
                this.dataList.push(json);
            }
        }
    }
}
</script>

<style scoped>

</style>

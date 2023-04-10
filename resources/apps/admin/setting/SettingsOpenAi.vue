<template>
    <table class="dsxui-formtable">
        <tbody>
        <tr>
            <td width="110" class="cell-label">AI聊天价格:</td>
            <td width="400" class="cell-input">
                <el-input type="text" v-model="settings.ai_chat_price"/>
            </td>
            <td class="cell-tips">AI聊天使用价格，单位：AI豆</td>
        </tr>
        <tr>
            <td class="cell-label">每日免费次数:</td>
            <td class="cell-input">
                <el-input type="text" v-model="settings.ai_day_free_amount"/>
            </td>
            <td class="cell-tips">每台设备每日可使用的免费次数</td>
        </tr>
        <tr>
            <td class="cell-label">计费规则说明:</td>
            <td class="cell-input">
                <el-input type="textarea" rows="5" v-model="settings.ai_billing_rules_desc"/>
            </td>
            <td class="cell-tips">AI使用计费规则说明</td>
        </tr>
        <tr>
            <td class="cell-label">代理商规则说明:</td>
            <td class="cell-input">
                <el-input type="textarea" rows="5" v-model="settings.ai_agent_rules_desc"/>
            </td>
            <td class="cell-tips">代理商规则说明</td>
        </tr>
        <tr>
            <td class="cell-label">邀请码使用规则说明:</td>
            <td class="cell-input">
                <el-input type="textarea" rows="5" v-model="settings.ai_invite_code_rules_desc"/>
            </td>
            <td class="cell-tips">邀请码使用规则说明</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "SettingsOpenAi",
        props: {
            settings: Object
        },
    }
</script>

<style scoped>

</style>

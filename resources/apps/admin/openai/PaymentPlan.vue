<template>
    <div>
        <header class="page-header">
            <div class="page-title">付费计划</div>
        </header>
        <div class="mainframe-content">
            <div class="content-block">
                <header class="table-edit-header">
                    <div class="display-flex">
                        <div class="font-16 font-bold flex">
                            <span>计划列表</span>
                        </div>
                        <div class="button-item">
                            <el-button type="primary" size="small" @click="onShowAdd">添加计划</el-button>
                        </div>
                    </div>
                </header>
                <el-tabs @tab-click="onClickTab" value="0">
                    <el-tab-pane label="全部" name="0"/>
                    <el-tab-pane label="天计划" name="1"/>
                    <el-tab-pane label="点数计划" name="2"/>
                </el-tabs>
                <el-table :data="dataList" @selection-change="onSelectionChange">
                    <el-table-column width="45" type="selection"/>
                    <el-table-column prop="id" width="80" label="ID"/>
                    <el-table-column prop="desc" label="名称"/>
                    <el-table-column prop="price" label="价格" width="100"/>
                    <el-table-column prop="type_des" label="类型" width="100"/>
                    <el-table-column width="100" label="支持内购">
                        <template slot-scope="scope">
                            {{scope.row.iap_enable === 1 ? '支持' : '不支持'}}
                        </template>
                    </el-table-column>
                    <el-table-column prop="iap_price" label="内购价格" width="100"/>
                    <el-table-column prop="iap_id" label="内购ID" width="200"/>
                    <el-table-column width="100" label="状态">
                        <template slot-scope="scope">
                            {{scope.row.enable === 1 ? '可用' : '已停用'}}
                        </template>
                    </el-table-column>
                    <el-table-column width="50" label="选项" align="right">
                        <template slot-scope="scope">
                            <a @click="onShowEdit(scope.row)">编辑</a>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="table-edit-footer">
                    <el-button size="small" type="primary" :disabled="selectionIds.length===0" @click="onDelete">
                        批量删除
                    </el-button>
                    <el-button size="small" :disabled="selectionIds.length===0" @click="onBatchUpdate({state:1})">
                        启用
                    </el-button>
                    <el-button size="small" :disabled="selectionIds.length===0" @click="onBatchUpdate({state:0})">
                        停用
                    </el-button>
                    <div class="flex"></div>
                </div>
            </div>
        </div>
        <el-dialog title="编辑信息" closeable :visible.sync="showDialog" :close-on-click-modal="false"
                   :close-on-press-escape="false">
            <table class="dsxui-formtable">
                <colgroup>
                    <col style="width: 80px;">
                    <col style="width: 200px;">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <td class="cell-label">类型</td>
                    <td class="cell-input">
                        <el-radio-group v-model="plan.type">
                            <el-radio :label="1">天数</el-radio>
                            <el-radio :label="2">点数</el-radio>
                        </el-radio-group>
                    </td>
                    <td class="cell-tips">计划类型</td>
                </tr>
                <tr>
                    <td class="cell-label">数量</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="plan.value"/>
                    </td>
                    <td class="cell-tips">按天填写天数，按点填写点数</td>
                </tr>
                <tr>
                    <td class="cell-label">价格</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="plan.price"/>
                    </td>
                    <td class="cell-tips"></td>
                </tr>
                <tr>
                    <td class="cell-label">支持内购</td>
                    <td class="cell-input">
                        <el-radio-group v-model="plan.iap_enable">
                            <el-radio :label="1">是</el-radio>
                            <el-radio :label="0">否</el-radio>
                        </el-radio-group>
                    </td>
                    <td class="cell-tips">是否支持iOS应用内购买</td>
                </tr>
                <tr>
                    <td class="cell-label">内购价格</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="plan.iap_price"/>
                    </td>
                    <td class="cell-tips">价格和appstore connect设置的价格保持一致</td>
                </tr>
                <tr>
                    <td class="cell-label">内购ID</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="plan.iap_id"/>
                    </td>
                    <td class="cell-tips">价格和appstore connect设置的产品保持一致保持一致</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td>
                        <el-button type="primary" size="medium" class="w100" @click="onSubmit">提交</el-button>
                    </td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "PaymentPlan",
        data() {
            return {
                type: 0,
                plan: {},
                dataList: [],
                selectionIds: [],
                showDialog: false,
            }
        },
        methods: {
            fetchList() {
                this.$get('/openai/paymentplan.getList', {type: this.type}).then(response => {
                    this.dataList = response.result.items;
                });
            },
            resetData() {
                this.plan = {
                    type: 1,
                    value: 1,
                    iap_enable: 1
                };
            },
            onDelete() {
                let ids = this.selectionIds.map((d) => d.id);
                this.$confirm('此操作将永久删除所选信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$post('/common/label.batchDelete', {ids}).then(response => {
                        this.fetchList();
                    });
                });
            },
            onSubmit() {
                let {plan} = this;
                if (!plan.value) {
                    this.$showToast('请填写数量');
                    return false;
                }
                if (!plan.price) {
                    this.$showToast('请填写价格');
                    return false;
                }

                this.$post('/openai/paymentplan.save', {plan}).then(() => {
                    this.showDialog = false;
                    this.fetchList();
                });
            },
            onShowAdd() {
                this.resetData();
                this.showDialog = true;
            },
            onShowEdit(d) {
                this.plan = d;
                this.showDialog = true;
            },
            onSelectionChange(val) {
                this.selectionIds = val;
            },
            onBatchUpdate(data) {
                let ids = this.selectionIds.map((d) => d.id);
                this.$post('/openai/paymentplan.batchUpdate', {ids, data}).then(response => {
                    this.fetchList();
                });
            },
            onClickTab(tab) {
                this.type = tab.name;
                this.fetchList();
            }
        },
        mounted() {
            this.fetchList();
        },
    }
</script>

<style scoped>

</style>

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
                <el-table :data="dataList" @selection-change="onSelectionChange">
                    <el-table-column width="45" type="selection"/>
                    <el-table-column prop="id" width="80" label="ID"/>
                    <el-table-column prop="title" label="名称"/>
                    <el-table-column prop="price" label="价格" width="100"/>
                    <el-table-column prop="iap_price" label="内购价格" width="100"/>
                    <el-table-column prop="iap_id" label="内购ID" width="100"/>
                    <el-table-column prop="type" label="类型" width="50" align="center"/>
                    <el-table-column prop="day_fee" label="每日费用" width="80" align="center"/>
                    <el-table-column width="100" label="可用" align="center">
                        <template slot-scope="scope">
                            <i class="el-icon-check font-18" v-if="scope.row.enable"/>
                            <i class="el-icon-close font-18" v-else/>
                        </template>
                    </el-table-column>
                    <el-table-column width="50" label="选项" align="right">
                        <template slot-scope="scope">
                            <a @click="onShowEdit(scope.row)">编辑</a>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="table-edit-footer">
                    <el-button size="small" type="primary" :disabled="selectionIds.length===0" @click="onBatchDelete">
                        批量删除
                    </el-button>
                    <el-button size="small" :disabled="selectionIds.length===0" @click="onBatchUpdate({enable:1})">
                        启用
                    </el-button>
                    <el-button size="small" :disabled="selectionIds.length===0" @click="onBatchUpdate({enable:0})">
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
                    <td class="cell-label">名称</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="product.title"/>
                    </td>
                    <td class="cell-tips">产品名称</td>
                </tr>
                <tr>
                    <td class="cell-label">类型</td>
                    <td class="cell-input">
                        <el-select class="w-100" v-model="product.type">
                            <el-option :value="1" label="一天"/>
                            <el-option :value="2" label="一周"/>
                            <el-option :value="3" label="一月"/>
                            <el-option :value="4" label="一季度"/>
                            <el-option :value="5" label="一年"/>
                            <el-option :value="6" label="终身会员"/>
                        </el-select>
                    </td>
                    <td class="cell-tips">计划类型</td>
                </tr>
                <tr>
                    <td class="cell-label">价格</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="product.price"/>
                    </td>
                    <td class="cell-tips"></td>
                </tr>
                <tr>
                    <td class="cell-label">原价</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="product.original_price"/>
                    </td>
                    <td class="cell-tips"></td>
                </tr>
                <tr>
                    <td class="cell-label">内购价格</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="product.iap_price"/>
                    </td>
                    <td class="cell-tips">价格和appstore connect设置的价格保持一致</td>
                </tr>
                <tr>
                    <td class="cell-label">内购ID</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="product.iap_id"/>
                    </td>
                    <td class="cell-tips">价格和appstore connect设置的产品保持一致保持一致</td>
                </tr>
                <tr>
                    <td class="cell-label">每日花费</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="product.day_fee"/>
                    </td>
                    <td class="cell-tips">平均每人花费</td>
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
    name: "Product",
    data() {
        return {
            type: 0,
            product: {},
            dataList: [],
            selectionIds: [],
            showDialog: false,
        }
    },
    methods: {
        fetchList() {
            this.$get('/openai/product.getList').then(response => {
                this.dataList = response.result.items;
            });
        },
        resetData() {
            this.product = {
                type: 1,
                iap_enable: 1
            };
        },
        onSubmit() {
            let {product} = this;
            if (!product.title) {
                this.$showToast('请填写产品名称');
                return false;
            }
            if (!product.price) {
                this.$showToast('请填写产品价格');
                return false;
            }
            this.$post('/openai/product.save', {product}).then(() => {
                this.showDialog = false;
                this.fetchList();
            });
        },
        onShowAdd() {
            this.resetData();
            this.showDialog = true;
        },
        onShowEdit(d) {
            this.product = d;
            this.showDialog = true;
        },
        onSelectionChange(val) {
            this.selectionIds = val;
        },
        onBatchUpdate(data) {
            let ids = this.selectionIds.map((d) => d.id);
            this.$post('/openai/product.batchUpdate', {ids, data}).then(() => {
                this.fetchList();
            });
        },
        onBatchDelete() {
            let ids = this.selectionIds.map((d) => d.id);
            this.$confirm('此操作将永久删除所选信息, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                this.$post('/openai/product.batchDelete', {ids}).then(() => {
                    this.fetchList();
                });
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

<template>
    <div>
        <header class="page-header">
            <div class="page-title">快捷分类</div>
        </header>
        <div class="mainframe-content">
            <div class="content-block">
                <header class="table-edit-header">
                    <div class="display-flex">
                        <div class="font-16 font-bold flex">
                            <span>分类列表</span>
                        </div>
                        <div class="button-item">
                            <el-button type="primary" size="small" @click="onShowAdd">添加分类</el-button>
                        </div>
                    </div>
                </header>
                <el-table :data="dataList">
                    <el-table-column prop="cate_id" label="ID" width="50"/>
                    <el-table-column prop="cate_name" label="名称"/>
                    <el-table-column prop="sort_num" width="200" label="排序"/>
                    <el-table-column width="80" label="可用" align="center">
                        <template slot-scope="scope">
                            <i class="el-icon-check font-18" v-if="scope.row.enable"/>
                            <i class="el-icon-close font-18" v-else/>
                        </template>
                    </el-table-column>
                    <el-table-column width="100" label="选项" align="right">
                        <template slot-scope="scope">
                            <a @click="onShowEdit(scope.row)">编辑</a> |
                            <a @click="onDelete(scope.row.cate_id)">删除</a>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <el-dialog title="编辑信息" closeable :visible.sync="showDialog" :close-on-click-modal="false"
                   :close-on-press-escape="false">
            <table class="dsxui-formtable">
                <colgroup>
                    <col style="width: 80px;">
                    <col style="width: 300px;">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <td class="cell-label">分类名称</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="category.cate_name"/>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="cell-label">显示顺序</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="category.sort_num"/>
                    </td>
                    <td class="cell-tips">数字越大越靠前</td>
                </tr>
                <tr>
                    <td class="cell-label">是否显示</td>
                    <td class="cell-input">
                        <el-radio-group v-model="category.enable">
                            <el-radio :label="1">是</el-radio>
                            <el-radio :label="0">否</el-radio>
                        </el-radio-group>
                    </td>
                    <td class="cell-tips"></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td>
                        <el-button type="primary" size="medium" class="w100" @click="onSave">提交</el-button>
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
    name: "PromptCategory",
    data() {
        return {
            dataList: [],
            category: {},
            showDialog: false,
            selectionIds: []
        }
    },
    mounted() {
        this.fetchList();
    },
    methods: {
        fetchList() {
            this.$get('/openai/prompt.category.getList').then(response => {
                this.dataList = response.result.items;
            });
        },
        resetData() {
            this.category = {sort_num: 0};
        },
        onDelete(cate_id) {
            this.$confirm('此操作将永久删除所选信息, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                this.$post('/openai/prompt.category.delete', {cate_id}).then(response => {
                    this.fetchList();
                });
            });
        },
        onSave() {
            let {category} = this;
            if (!category.cate_name) {
                this.$showToast('请填写分类名称');
                return false;
            }
            this.$post('/openai/prompt.category.save', {category}).then(response => {
                this.fetchList();
                this.showDialog = false;
            });
        },
        onShowAdd() {
            this.resetData();
            this.showDialog = true;
        },
        onShowEdit(d) {
            this.category = d;
            this.showDialog = true;
        },
    }
}
</script>

<style scoped>

</style>

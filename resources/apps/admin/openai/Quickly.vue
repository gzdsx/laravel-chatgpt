<template>
    <div>
        <header class="page-header">
            <div class="page-title">快捷工具</div>
        </header>
        <div class="mainframe-content">
            <div class="content-block">
                <header class="table-edit-header">
                    <div class="display-flex">
                        <div class="font-16 font-bold flex">
                            <span>工具列表</span>
                        </div>
                        <div class="button-item">
                            <el-button type="primary" size="small" @click="onShowAdd">添加工具</el-button>
                        </div>
                    </div>
                </header>
                <el-tabs @tab-click="onClickTab" value="0">
                    <el-tab-pane label="全部" name="0"/>
                    <el-tab-pane
                            v-for="(cate,index) in categoryList"
                            :label="cate.cate_name"
                            :name="''+cate.cate_id+''"
                            :key="index"
                    />
                </el-tabs>
                <el-table :data="dataList" :loading="loading" @selection-change="onSelectionChange">
                    <el-table-column width="45" type="selection"/>
                    <el-table-column label="图标" width="70">
                        <template slot-scope="scope">
                            <el-image class="img-40" fit="cover" :src="scope.row.icon"/>
                        </template>
                    </el-table-column>
                    <el-table-column prop="title" label="名称" width="150"/>
                    <el-table-column prop="route" label="路由" width="200"/>
                    <el-table-column prop="category.cate_name" label="分类" width="100"/>
                    <el-table-column prop="template" label="模版"/>
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
                    <div class="flex"></div>
                    <el-pagination
                            background
                            layout="prev, pager, next, total"
                            :total="total"
                            :page-size="pageSize"
                            @current-change="onPageChange"
                    >
                    </el-pagination>
                </div>
            </div>
        </div>
        <el-dialog title="编辑信息" closeable :visible.sync="showDialog" :close-on-click-modal="false"
                   :close-on-press-escape="false" width="800px">
            <table class="dsxui-formtable">
                <colgroup>
                    <col style="width: 80px;">
                    <col style="width: 400px;">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <td class="cell-label">工具图标</td>
                    <td class="cell-input">
                        <div @click="showPicker=true">
                            <el-image :src="quickly.icon" fit="cover" class="w80 h80" v-if="quickly.icon"/>
                            <div class="w80 h80 img-placeholder" v-else></div>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="cell-label">工具名称</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="quickly.title"></el-input>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="cell-label">工具分类</td>
                    <td class="cell-input">
                        <el-select class="w-100" v-model="quickly.cate_id">
                            <el-option
                                    v-for="(cate,idx) in categoryList"
                                    :key="idx"
                                    :label="cate.cate_name"
                                    :value="cate.cate_id"
                            ></el-option>
                        </el-select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="cell-label">页面路由</td>
                    <td class="cell-input">
                        <el-input size="medium" v-model="quickly.route"></el-input>
                    </td>
                    <td class="cell-tips">APP内页面访问路由,默认:chat-completion</td>
                </tr>
                <tr>
                    <td class="cell-label">输入提示</td>
                    <td class="cell-input">
                        <el-input type="textarea" rows="3" size="medium" v-model="quickly.tips"/>
                    </td>
                    <td class="cell-tips">文本框上方提示语</td>
                </tr>
                <tr>
                    <td class="cell-label">默认内容</td>
                    <td class="cell-input">
                        <el-input type="textarea" rows="5" size="medium" v-model="quickly.prompt"/>
                    </td>
                    <td class="cell-tips">输入框默认内容</td>
                </tr>
                <tr>
                    <td class="cell-label">模版</td>
                    <td class="cell-input">
                        <el-input type="textarea" rows="5" v-model="quickly.template"/>
                    </td>
                    <td class="cell-tips">prompt模版,用户输入提示与用{prompt}代替</td>
                </tr>
                <tr>
                    <td class="cell-label">选项</td>
                    <td class="cell-input">
                        <el-input type="textarea" rows="5" v-model="quickly.options"/>
                    </td>
                    <td class="cell-tips">自定义请求选项,标准ini模版，每行一个</td>
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
        <image-picker v-model="showPicker" :params="{width:128,crop:true}" @confirm="onChooseImage"/>
    </div>
</template>

<script>
    import PaginationMixin from "../mixins/PaginationMixin";

    export default {
        name: "Quickly",
        data() {
            return {
                quickly: {},
                showDialog: false,
                showPicker: false,
                categoryList: [],
                params: {
                    title: '',
                    cate_id: 0
                },
                listApi: '/openai/quickly.getList'
            }
        },
        mixins: [PaginationMixin],
        methods: {
            fetchCategoryList() {
                this.$get('/openai/quickly.category.getList').then(response => {
                    this.categoryList = response.result.items;
                });
            },
            resetData() {
                this.quickly = {route: 'chat-completion'};
            },
            onBatchDelete() {
                let ids = this.selectionIds.map((d) => d.id);
                this.$confirm('此操作将永久删除所选信息, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$post('/openai/quickly.batchDelete', {ids}).then(response => {
                        this.fetchList();
                    });
                });
            },
            onSave() {
                let {quickly} = this;
                if (!quickly.title) {
                    this.$showToast('请填写名称');
                    return false;
                }
                this.$post('/openai/quickly.save', {quickly}).then(response => {
                    this.fetchList();
                    this.showDialog = false;
                });
            },
            onShowAdd() {
                this.resetData();
                this.showDialog = true;
            },
            onShowEdit(d) {
                this.quickly = d;
                this.showDialog = true;
            },
            onChooseImage(data) {
                this.quickly.icon = data.image;
            },
            onClickTab(tab) {
                this.params.cate_id = tab.name;
                this.onSearch();
            }
        },
        mounted() {
            this.fetchList();
            this.fetchCategoryList();
        }
    }
</script>

<style scoped>

</style>

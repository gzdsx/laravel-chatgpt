<template>
    <div>
        <header class="page-header">
            <div class="page-title">用户管理</div>
        </header>

        <div class="mainframe-content">
            <div class="content-block">
                <div class="table-edit-header">
                    <div class="table-edit-title">请求记录</div>
                </div>
                <el-table :data="dataList" v-loading="loading" @selection-change="onSelectionChange">
                    <el-table-column width="45" type="selection"/>
                    <el-table-column prop="user.nickname" label="用户" width="200"/>
                    <el-table-column prop="prompt" label="提示词"/>
                    <el-table-column prop="created_at" width="170" label="请求时间" align="right"/>
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
                            :current-page="page"
                            @current-change="onPageChange"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PaginationMixin from "../mixins/PaginationMixin";

    export default {
        name: "RequestLog",
        mixins: [PaginationMixin],
        data() {
            return {
                params: {},
                listApi: '/openai/requestlog.getList'
            }
        },
        methods: {
            onBatchDelete() {
                let ids = this.selectionIds.map((d) => d.id);
                this.$confirm('此删除将无法恢复, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$post('/openai/requestlog.batchDelete', {ids}).then(() => {
                        this.fetchList();
                    });
                });
            },
        },
        mounted() {
            this.fetchList();
        },
    }
</script>

<style scoped>

</style>

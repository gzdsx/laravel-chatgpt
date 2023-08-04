import Product from "./Product.vue";
import RequestLog from "./RequestLog.vue";
import PromptModel from "./PromptModel.vue";
import PromptCategory from "./PromptCategory.vue";

module.exports = [
    {path: '/openai/product', component: Product, meta: {title: '付费计划', group: 'openai'}},
    {path: '/openai/prompt-model', component: PromptModel, meta: {title: '快捷工具', group: 'openai'}},
    {path: '/openai/prompt-category', component: PromptCategory, meta: {title: '工具分类', group: 'openai'}},
    {path: '/openai/request-log', component: RequestLog, meta: {title: '请求记录', group: 'openai'}},
]

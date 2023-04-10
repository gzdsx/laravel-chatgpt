import PaymentPlan from "./PaymentPlan";
import Quickly from "./Quickly";
import QuicklyCategory from "./QuicklyCategory";

module.exports = [
    {path: '/openai/payment-plan', component: PaymentPlan, meta: {title: '付费计划', group: 'openai'}},
    {path: '/openai/quickly', component: Quickly, meta: {title: '快捷工具', group: 'openai'}},
    {path: '/openai/quickly-category', component: QuicklyCategory, meta: {title: '工具分类', group: 'openai'}},
]

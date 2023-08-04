export default [
    {
        name: '首页',
        fullName: '首页',
        icon: 'icon-home',
        path: '/',
        group: 'home',
        children: [
            {
                name: '后台首页',
                path: '/',
                isLink: false
            },
            {
                name: '网站首页',
                path: '/',
                isLink: true
            },
        ]
    },
    {
        name: '设置',
        fullName: '系统设置',
        icon: 'icon-setting',
        path: '/settings',
        group: 'settings',
        children: [
            {
                name: '参数设置',
                path: '/settings',
                isLink: false
            },
        ]
    },
    {
        name: '用户',
        fullName: '用户管理',
        icon: 'icon-people',
        path: '/user/list',
        group: 'user',
        children: [
            {
                name: '用户列表',
                path: '/user/list',
                isLink: false,
                visible: true
            },
            {
                name: '用户分组',
                path: '/user/group',
                isLink: false,
                visible: true
            },
            {
                name: '管理员管理',
                path: '/admin/user',
                isLink: false,
                visible: true
            },
            {
                name: '管理员分组',
                path: '/admin/group',
                isLink: false,
                visible: true
            },
        ]
    },
    {
        name: 'AI',
        fullName: 'OpenAI',
        icon: 'icon-form',
        path: '/openai/product',
        group: 'openai',
        children: [
            {
                name: '付费计划',
                path: '/openai/product',
                isLink: false
            },
            {
                name: '快捷工具',
                path: '/openai/prompt-model',
                isLink: false
            },
            {
                name: '快捷分类',
                path: '/openai/prompt-category',
                isLink: false
            },
            {
                name: '请求记录',
                path: '/openai/request-log',
                isLink: false
            }
        ]
    },
    {
        name: '文章',
        fullName: '文章管理',
        icon: 'icon-form',
        path: '/post/list',
        group: 'post',
        children: [
            {
                name: '文章管理',
                path: '/post/list',
                isLink: false
            },
            {
                name: '文章分类',
                path: '/post/category',
                isLink: false
            }
        ]
    },
    {
        name: '页面',
        fullName: '页面管理',
        icon: 'icon-info',
        path: '/page/list',
        group: 'page',
        children: [
            {
                name: '页面列表',
                path: '/page/list',
                isLink: false
            },
            {
                name: '页面分类',
                path: '/page/category',
                isLink: false
            }
        ]
    },
    {
        name: '微信',
        fullName: '微信公众号',
        icon: 'icon-wechat-fill',
        path: '/wechat/menu',
        group: 'wechat',
        children: [
            {
                name: '菜单管理',
                path: '/wechat/menu',
                isLink: false
            },
            {
                name: '素材管理',
                path: '/wechat/material',
                isLink: false
            }
        ]
    },
    {
        name: '其他',
        fullName: '其他',
        icon: 'icon-skin',
        path: '/material/list',
        group: 'common',
        children: [
            {
                name: '素材管理',
                path: '/material/list',
                isLink: false
            },
            {
                name: '链接管理',
                path: '/link/list',
                isLink: false
            },
            {
                name: '区域管理',
                path: '/district/list',
                isLink: false
            },
            {
                name: '内容模块',
                path: '/block/list',
                isLink: false
            },
            {
                name: '广告管理',
                path: '/ad/list',
                isLink: false
            },
            {
                name: '标签管理',
                path: '/label/list',
                isLink: false
            },
            {
                name: '菜单管理',
                path: '/menu/list',
                isLink: false
            },
            {
                name: '快递管理',
                path: '/express/list',
                isLink: false
            },
            {
                name: '客服管理',
                path: '/kefu/list',
                isLink: false
            },
        ]
    },
];

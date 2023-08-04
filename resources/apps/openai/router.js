import ChatApp from "./ChatApp";

const router = new VueRouter({
    routes: [
        {path: '/', component: ChatApp, meta: {title: 'AI聊天'}},
    ]
});

router.beforeEach(((to, from, next) => {
    let {title} = to.meta;
    if (title) {
        document.title = title;
    } else {
        document.title = window.siteName;
    }
    if (to.meta.auth) {
        if (to.name === 'wxlogin') {
            next();
        } else {
            let accessToken = localStorage.getItem('accessToken');
            if (accessToken) {
                next();
            } else {
                localStorage.setItem('redirect', window.location.href);
                next('/wxlogin');
                //return false;
            }
        }
    }
    next();
}));

module.exports = router;

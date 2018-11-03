export default function auth(next, router) {
    if (!localStorage.getItem('_token')) {
        return router.push({ name: 'Login' });
    }
    return next();
}
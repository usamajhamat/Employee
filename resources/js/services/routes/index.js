import admin from './admin';
import auth from './auth';

const routes = [
    ...admin,
    ...auth,
]

export default routes;

import axios from "axios";

const api = axios.create({
    headers: { "X-Requested-With": "XMLHttpRequest"}
});

api.interceptors.request.use(
    request => {
        console.log(request);
        if (request.status === 302) {
            this.$router.replace({
            path: "/login",
            query: { redirect: router.currentRoute.fullPath }
            });
        } else {
            return request;
        }
    },
    error => {
        return Promise.reject(error);
    }
)


export default api;
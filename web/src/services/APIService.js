import axios from 'axios'

function formatParams ( params ) {
    if(params) {
        var format = new URLSearchParams();
        for(let key in params) {
            let value = params[key];
            if(Array.isArray(value)) {
                for(let i = 0; i < value.length; i++) {
                    if(value[i] !== undefined && value[i] !== null) {
                        format.append(key, value[i]);
                    }
                }
            } else if(value !== undefined && value !== null) {
                format.append(key, value)
            }
        }
        return format;
    }
}
    
export default {

    get ( url, params ) {
        return new Promise((resolve, reject) => {
            axios.get( url, { params: formatParams(params) } )
            .then(res => resolve(res.data))
            .catch(res => reject(res.response));
        });
    },

    post ( url, data, params ) {
        return new Promise((resolve, reject) => {
            axios.post( url, data, { params: formatParams(params) } )
            .then(res => resolve(res.data))
            .catch(res => reject(res.response));
        });
    },

    put ( url, data, params ) {
        return new Promise((resolve, reject) => {
            axios.put( url, data, { params: formatParams(params) } )
            .then(res => resolve(res.data))
            .catch(res => reject(res.response));
        });
    },

    delete ( url, params ) {
        return new Promise((resolve, reject) => {
            axios.delete( url, { params: formatParams(params) } )
            .then(res => resolve(res.data))
            .catch(res => reject(res.response));
        });
    },

    upload ( url, file ) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', file.type);
        return new Promise((resolve, reject) => {
            axios.post( url, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
            .then(res => resolve(res.data))
            .catch(res => reject(res.response));
        });
    },

    getLink ( url ) {
        return axios.defaults.baseURL + url;
    },

}

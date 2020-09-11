import axios from 'axios'

export default {

  login ( email, password ) {
    return new Promise((resolve, reject) => {
      axios.post( '/login', { email, password }, {} )
      .then(res => {
        let token = res.headers['access-token'];
        let user = res.data;
        resolve({ user, token });
      })
      .catch(res => reject(res.response));
    });
  },

}

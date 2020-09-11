export default {

    init () {
      return { fields: {}, messages: new Set() };
    },
  
    process (response, translate) {
      const errors = this.init();
      if (response.status === 400) {
        for(const field in response.data) {
          errors.fields[field] = true;
          response.data[field].forEach(message => errors.messages.add(translate(message)));
        }
      }
      return errors;
    },
  
  }
  
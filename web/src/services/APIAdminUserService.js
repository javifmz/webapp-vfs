import APIService from '@/services/APIService'

export default {

  getUsers ( view, size ) {
    const params = { ...view, size };
    return APIService.get( '/admin/users', params );
  },

  getUser ( userId ) {
    return APIService.get( '/admin/users/' + userId );
  },

  addUser( user ) {
    return APIService.post( '/admin/users', user );
  },

  updateUser( userId, user ) {
    return APIService.put( '/admin/users/' + userId, user );
  },

  updateUserStatus( userId, status ) {
    return APIService.put( '/admin/users/' + userId + '/status', { status } );
  },

  updateUserPassword( userId, password, passwordConfirmation ) {
    return APIService.put( '/admin/users/' + userId + '/password', { password, passwordConfirmation } );
  },

  deleteUser( userId ) {
    return APIService.delete( '/admin/users/' + userId );
  },

}

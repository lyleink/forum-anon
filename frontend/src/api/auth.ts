import request from './request'

export function login(username: string, password: string, remember: boolean) {
  return request.post('/auth/login', { username, password, remember })
}

export function logout() {
  return request.post('/auth/logout')
}

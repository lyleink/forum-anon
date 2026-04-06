import request from './request'

export function getUsers(params: { page?: number; pageSize?: number; keyword?: string }) {
  return request.get('/users', { params })
}

export function createUser(data: { username: string; password: string; email?: string; role?: string }) {
  return request.post('/users', data)
}

export function updateUser(id: number, data: { username?: string; email?: string; role?: string; status?: string }) {
  return request.put(`/users/${id}`, data)
}

export function deleteUser(id: number) {
  return request.delete(`/users/${id}`)
}

export function batchDeleteUsers(ids: number[]) {
  return request.delete('/users/batch', { data: { ids } })
}

export function resetPassword(id: number) {
  return request.post(`/users/${id}/reset-password`)
}

export function exportUsers() {
  return request.get('/users/export', { responseType: 'blob' })
}

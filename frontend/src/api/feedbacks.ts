import request from './request'

export function getFeedbacks(params: { page?: number; pageSize?: number; keyword?: string; category?: string; status?: string }) {
  return request.get('/feedbacks', { params })
}

export function getFeedback(id: number) {
  return request.get(`/feedbacks/${id}`)
}

export function createFeedback(data: { category: string; content: string }) {
  return request.post('/feedbacks', data)
}

export function updateFeedbackStatus(id: number, status: string) {
  return request.put(`/feedbacks/${id}/status`, { status })
}

export function updateFeedbackNotes(id: number, notes: string) {
  return request.put(`/feedbacks/${id}/notes`, { notes })
}

export function deleteFeedback(id: number) {
  return request.delete(`/feedbacks/${id}`)
}

export function exportFeedbacks() {
  return request.get('/feedbacks/export', { responseType: 'blob' })
}

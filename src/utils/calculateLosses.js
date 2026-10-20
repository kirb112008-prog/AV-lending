const MAX_LOSS_RATE = 0.6
const LOSS_RATE_PER_MINUTE = 0.4 / 15
const RECOVERABLE_SHARE = 0.75

export function calculateLosses({ requestsPerMonth, averageCheck, responseMinutes, conversionPercent }) {
  const lossRate = Math.min(MAX_LOSS_RATE, responseMinutes * LOSS_RATE_PER_MINUTE)
  const lostRequests = Math.round(requestsPerMonth * lossRate)
  const lostRevenue = Math.round(lostRequests * (conversionPercent / 100) * averageCheck)
  const recoverableRevenue = Math.round(lostRevenue * RECOVERABLE_SHARE)

  return { lostRequests, lostRevenue, recoverableRevenue }
}

export function formatNumber(value) {
  return value.toLocaleString('ru-RU')
}

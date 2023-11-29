def lis_ending_at(arr, i):
  if i == 0:
    return 1
  max_len = 1
  for j in range(i):
    if arr[j] < arr[i]:
      len_j = lis_ending_at(arr, j)
      max_len = max(max_len, len_j + 1)
  return max_len

def lis(arr):
  max_len = 0
  for i in range(len(arr)):
    len_i = lis_ending_at(arr, i)
    max_len = max(max_len, len_i)
  return max_len


arr = [10, 22, 9, 33, 21, 50, 41, 60]
n = len(arr)
 
print("Length of lis is", lis(arr))

def max_value(coin, n):
    if n <= 0:
        return 0
    elif n == 1:
        return coin[0]
    elif n == 2:
        return max(coin[0], coin[1])

    take_current = coin[n - 1] + max_value(coin, n - 2)
    skip_current = max_value(coin, n - 1)

    return max(take_current, skip_current)

coin = [ 20, 50, 20, 5, 2 ] 
n = len(coin)
result = max_value(coin, n)
print("Maximum value:", result)

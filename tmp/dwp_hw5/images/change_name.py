import os
import re

# 指定目錄
directory = 'c:\我的東西\常用\交大\三上\DWP\hw4\HW4_111550008_蔡東霖\images/cards'  # 替換成實際目錄

flowers = {"clubs": 1,
           "diamonds": 2,
           "hearts": 3,
           "spades": 4}

flowersA = {"clubs": 1,
           "diamonds": 2,
           "hearts": 3,
           "spades": 4,
           "spades2": 55}

flowersJQK = {"clubs2": 1,
           "diamonds2": 2,
           "hearts2": 3,
           "spades2": 4,
           "clubs": 55,
           "diamonds": 56,
           "hearts": 57,
           "spades": 58}

numbers = {"jack": 11,
           "queen": 12,
           "king": 13}

# 遍歷指定目錄中的檔案
for filename in os.listdir(directory):
    match = re.match(r"(\d+)_of_([a-z]+)\.svg", filename)
    if match:
        # 提取數字並構建新的檔案名稱
        number = match.group(1)
        flower = match.group(2)
        new_filename = f"{number}_{flowers[flower]}.svg"
        
        # 取得完整路徑
        old_file = os.path.join(directory, filename)
        new_file = os.path.join(directory, new_filename)
        
        # 重命名檔案
        os.rename(old_file, new_file)
        continue

    match = re.match(r"ace_of_([a-z0-9]+)\.svg", filename)
    if match:
        # 提取數字並構建新的檔案名稱
        flower = match.group(1)
        new_filename = f"1_{flowersA[flower]}.svg"
        
        # 取得完整路徑
        old_file = os.path.join(directory, filename)
        new_file = os.path.join(directory, new_filename)
        
        # 重命名檔案
        os.rename(old_file, new_file)
        continue

    match = re.match(r"([a-z]+)_of_([a-z0-9]+)\.svg", filename)
    if match:
        # 提取數字並構建新的檔案名稱
        number = match.group(1)
        flower = match.group(2)
        new_filename = f"{numbers[number]}_{flowersJQK[flower]}.svg"
        
        # 取得完整路徑
        old_file = os.path.join(directory, filename)
        new_file = os.path.join(directory, new_filename)
        
        # 重命名檔案
        os.rename(old_file, new_file)
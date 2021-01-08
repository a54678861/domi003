import requests
import sqlite3
import time
import datetime
headers = {
    # 假装自己是瀏覽器
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.135 Safari/537.36',
    # 把Cookie塞進来
    'Cookie': 'G_ENABLED_IDPS=google; __cfduid=d58480077fb9db428a9b5602e37c0629e1602564536',
    'token': '5efca7367aeca35edfaf7ec3.GPJEsW6lUC1625562839594.113955087009517311149'
}
conn = sqlite3.connect('C:/Apache24/htdocs/003/sql.db') # ~代表路徑
c = conn.cursor()
catchlv = 999 #從幾等開始抓?
times = 1400 #要抓幾頁?
for i in range(times):
    sheet = requests.get('https://mykirito.com/api/user-list?lv=70&page='+str(i),headers=headers)
    sheet = sheet.json()
    lisr = sheet['userList']
    date = time.strftime("%Y-%m-%d", time.localtime())
    for i in lisr:
        if i['lv'] > 0 :
            name = i["nickname"]
            if name =='茅場晶彥':
                continue
            lv = i["lv"]
            uid = i["uid"]
            status = i["status"]
            floor = i["floor"]
            title = i["title"]
            character = i["character"]
            ttl = list((name,floor,character,title,date))
            c.execute('insert into player(name,floor,chara,title,date) values (?,?,?,?,?)',ttl)
            conn.commit()
                
conn.close()



            


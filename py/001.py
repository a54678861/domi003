import requests
import sqlite3

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
times = 700 #要抓幾頁?
c.execute('DELETE FROM uberhits')
conn.commit()
c.execute('DELETE FROM sqlite_sequence')
conn.commit()
for i in range(times):
    sheet = requests.get('https://mykirito.com/api/user-list?lv=999&page='+str(i),headers=headers)
    sheet = sheet.json()
    lisr = sheet['userList']
    for i in lisr:
        if i['lv'] > 30 :
            name = i["nickname"]
            if name =='茅場晶彥':
                continue
            lv = i["lv"]
            uid = i["uid"]
            status = i["status"]
            floor = i["floor"]
            character = i["character"]
            sheet2 = requests.get('https://mykirito.com/api/profile/'+str(uid),headers=headers)
            link = 'https://mykirito.com/profile/'+str(uid)
            sheet2 = sheet2.json()
            lisr2 = sheet2['profile']
            floor2 = lisr2.get("floor2",0)
            murder = lisr2['murder']
            defDeath = lisr2['defDeath']
            floor = str(floor) + "之" + str(floor2)
            tt = '可能有套'
            if (murder*5)+1 > defDeath:
                tt = (murder*5)+1 - defDeath
                ttl = list((name,lv,link,status,character,tt))
                c.execute('insert into uberhits(name,lv,link,stat,chara,count) values (?,?,?,?,?,?)',ttl)
                conn.commit()
                
conn.close()



            


#!/usr/bin/env python

import os
from bottle import run, route, Bottle, template

@route('/index/<id>')
def home():
    if id = None:
    	return template('frontTemplate')

run(host='localhost', port=8080, debug=True)

from bottle import run
run(reloader=True)


class GoogleAccount_obj(object):
	"""contains necessary session data for GoogleAccount user"""
	def __init__(self, arg):
		super(GoogleAccount_obj, self).__init__()
		self.arg = arg
		
class OtherEmailObject(object):
	"""contains necessary session data for OtherEmail user"""
	def __init__(self, arg):
		super(OtherEmailObject, self).__init__()
		self.arg = arg
		
	userId = int
	email = ''

#!/usr/bin/python

import MySQLdb

# Open database connection
db = MySQLdb.connect("localhost","testuser","test123","TESTDB" )

# prepare a cursor object using cursor() method
cursor = db.cursor()

# Prepare SQL query to INSERT a record into the database.
sql = """INSERT INTO EMPLOYEE(FIRST_NAME,
         LAST_NAME, AGE, SEX, INCOME)
         VALUES ('Mac', 'Mohan', 20, 'M', 2000)"""
try:
   # Execute the SQL command
   cursor.execute(sql)
   # Commit your changes in the database
   db.commit()
except:
   # Rollback in case there is any error
   db.rollback()

# disconnect from server
db.close()


class SQLLibrary {
"""query lookup specific to this page"""
	def __init__(self):

	def CheckUserExists(username):
		pass

	def CompareUserPassword(username, password, salt=1):
		return -1

	def ChangeUserPassword(username, newPass, oldPass, salt=1):
		return -1

	def createUser(username, newPass, salt=1):
		return -1

	def set_SigleSignOn_Account(username, password, accountinfo, salt=1):
		return -1

	def checkIs_SignleSignOn(accountinfo):
		return -1

	tableInfo = []

	def getTableInfo():
		tables = getAllTables()
		for i in 


	def getAllTables():
		cursor = db.cursor()
		query = "select * from %s",table_schema
		tables = []
		cursor.execute(query)
		results = cursor.fetchall()
		for row in results:


			columnQuery = """SELECT all from information_schedma.columns
				where table_name = %s""", row['name']
			cursor.execute(query)
			colResults = cursor.fetchall()

			for coloumn in colResults:
				tables[coloumn] = row
		self.tableInfo = tables
}
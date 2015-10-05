import os
import pickle
from bottle import route, run, template

class UserClass(object):
	"""docstring for UserClass"""
	

	def __init__(self, username, password):
		if CheckUserExists(username):
			if CheckUserPass(username, password):
				self.username = username

				query = 'select tablename from %s where username = %s', userTable, username
				ret = Sql_Query(query)
				if ret[0]:
					self.tablename = ret[0]
				else:
					tablename = CreateUserTable(username)
				self.PopulateSessionTable()
	
	username = ''
	sessions = [[]]
	tablename = ''
	currentSession = None

	def ExecuteCurrentSession(self):
		if self.currentSession is None:
			return -1
		else:
			if self.status is 'fileLoad':
				return FileLoad()
			else if self.status is 'columnSelect':
				return ColumnSelectProcessor()
			else if self.status is 'newAttributeValues':
				return AttributeValueProcessor()


	def PopulateSessionTable(self):
		if self.tablename is '':
			return -1
		else:
			query = 'select id,status from %s', self.tablename
			ret = Sql_Query(query)
			sessionReturn = ret.fetchall()
			self.sessions = [[]]
			if len(sessionReturn) > 0:
				for s in sessionReturn:
					self.sessions[] = [sessionReturn[0],sessionReturn[1]]
			else:
				return -1


	def LoadSession(self, sessionId):
		if len(self.sessions) == 0
			return -1
		else:
			for i in self.sessions:
				if i[0] == sessionId:
					#know that it is a valid sessions, so load it from the table
					sessionQuery = 'select data from %s where id=%s', self.tablename, sessionId
					ret = Sql_Query(sessionQuery).fetchall()
					if not ret[0]:
						return -1
					else:
						self.currentSession = pickle.loads(ret[0])


	def StoreSession(self):
		if self.currentSession not None:
			loadString = pickle.dumps(self.currentSession)
			loadQuery = "update %s, set data=%s, status=%s where id=%s", self.tablename, loadString, self.currentSession.status, self.currentSession.id
			ret = Sql_Query(loadQuery)
			if ret:
				return 1
			else:
				return -1
		else:
			return -1


	def TestSession(expectedStatus, sessionId):
		if len(self.sessions) == 0:
			return -1
		for e in self.sessions:
			if e[0] == sessionId:
				if e[1] == expectedStatus:
					return 1;

		return -1

	def TestIsSession(sessionId):
		if len(self.sessions) == 0:
			return -1
		for e in self.sessions:
			if e[0] == sessionId:
				return 1


	def ChangeUsername(newUsername):


	def ChangePassword(currentPassword, newPassword):



#user functions
def LoadUser(username, password):
	query = "select password from %s where username = %s", userTable, username
	ret = Sql_Query(query)
	if ret:
		if ret[0] == password:
import os
import pickle
from bottle import route, run, template



#templates

loginPage = """
<html>
<body>

%if errors:
	<div>{{errors}}</div>

<form id="login_form" action="self" method="post">

<div id="userLogin">
<div>
Username: <input type="textline" name="username"></input>
</div>
<div>
Password: <input type="textline" name="password"></input>
</div>
<div>
<input type="button" name="createAccount" value="createAccount"></input>
<input type="button" name="login" value="login"></input>
</div>
</div>

<div id="googleLogin">
<input type="button" name="googleLogin" value="googleLogin"></input>
</div>

</form>


</body>
</html>
"""


@route('/login/<value>')
def login():
	errors = []

	if value is None || value is '':
		return template(loginPage)

	else if value == 'newUser':
		userPass = _NewUserRequest(errors)
		if not userPass
			return template(loginPage, errors=errors)
		else
			user = LoadUser(userPass[0], userPass[0])
			return user.GetUserFrontPage()

	else if value = 'google auth':
		#probably getting account id string and stuff, confirm is google (ssl)
		userPass = GoogleAuthRequest(errors, googleId)
		if not userPass:
			return template(loginPage, errors=errors)
		else:
			user = LoadUser(userPass[0], userPass[1])
			return user.GetUserFrontPage()

	else if value = 'login':
		
		userPass = LoginRequest(errors)
		if not userPass:
			return template(loginPage, errors=errors)
		user = LoadUser(username, password)
				return user.GetUserFrontPage()
		else:
			return template(loginPage, errors='Please Enter Username And Password')

def _NewUserRequest(errors):
	username = request.forms.get("username")
	password = request.forms.get("password")
	
	if username && password:
		if CheckUserExists(username):
			errors[] = 'User Already Exists'
		else:
			if CreateUser(username, password):
				return [username,password]
			else:
				errors[] = 'Error Creating User'
	else:
		errors[] = 'Please Enter Username And Password'
	return -1


def GoogleAuthRequest(errors):
	username = request.post.get('googleId')
	password = salt	

	if not CheckUserExists(username):
			CreateUser(username, password)
			
	return [username, password]

def LoginRequest(errors):
	username = request.forms.get("username")
	password = request.forms.get("password")
	
	if username && password:
		if not CheckUserExists(username) or not CheckUserPass(username, password):
			errors[] = 'Incorrect Username Or Password'
		else:
			return [username, password]
	else:
		errors[] = 'Please Enter A Username And Password'
	return -1

def CreateUser(username, password):
	if CheckUserExists(username):
		return -1
	query = "insert into %s username=%s, password=%s", userTable, username, password
	ret = Sql_Query(query)
	if not ret:
		return -1
	user = UserClass(username, password)
	return user



def CheckUserExists(username):
	query = "select id from %s where username = %s", userTable, username
	ret = Sql_Query(query);
	if ret[0]:
		return 1
	else:
		return -1


def CompareSaltyPass(username, saltyPass):
	query = 'select password, salt from %s where username = %s', userTable, username
	ret = Sql_Query(query)
	if ret:
		if encryptSalt(ret[0], ret[1]) is saltyPass:
			return 1
	return -1

def CheckUserPass(username, password):
	query = "select password,salt from %s where username = %s", userTable, username
	ret = Sql_Query(query)
	if ret:
		if saltDecrypt(ret[1], ret[0]) == password:
			return 1
		else:
			return -1
	else:
		return -1


def LoadUser(username, password):
	query = "select password from %s where username = %s", userTable, username
	ret = Sql_Query(query)
	if ret:
		if ret[0] == password:


def CreateUserTable(username):
	query = 'create table %s (%s)', sessionTablePrefix+username, sessionTableStructure
	query = 'insert into %s (name) values (%s)', userSessions, username



@route('/userPage/<action>')
def UserFrontPage(action):
	if action is None:
		user = request.forms.get('Hidden_Username')
		saltyPass = request.forms.get('Salty_Pass')
		if CheckUserExists(user):
			if CompareSaltyPass(user, saltyPass):
				CurrentUser = LoadUser(user, saltyPass)
				return CurrentUser.GetUserFrontPage()
		return template(loginPage, errors='Please Log In Again')

	if action is 'LoadSession':
		user = request.forms.get('Hidden_Username')
		saltyPass = request.forms.get('Salty_Pass')
		if CheckUserExists(user):
			if CompareSaltyPass(user, saltyPass):
				user = LoadUser(user, saltyPass)
				sessionId = request.forms.get('sessionId')
				if user.TestIsSession(sessionId):
					user.LoadSession(sessionId)
					return user.ExecuteCurrentSession()

	if action is 'New Session':



@route('/fileLoad/<action>')
def FileLoad(action):
	if action is None:
		#need to print the page only
	else if action is 'File':
		fp = request.files.get('File_To_Upload')
		if fp:
			ProcessFile(fp)
		else:
			#reprint page with errors

	else if action is 'Text':
		text = request.forms.get('Text_To_Upload')
		if text:
			ProcessText(text)
		else:
			#reprint page with errors


@route('/columnSelect/<action>')
def ColumnSelectProcessor(action):
	if action is none:
		#print column selection
	if action is processColumns:
		columnInfo = request.forms.get('ColumnStuffs')


@route('/newAttributeValues/<action>')
def AttributeValueProcessor(action):
	if action is None:
		#print page
	else if action is 'NewValues':
		newValues = request.form.get('New_Attribute_Values')





class User {

	def ExecuteCurrentSession(self):
		if self.currentSession is None:
			return -1
		else:
			if self.status is 'fileLoad':
				FileLoad()
			else if self.status is 'columnSelect':
				ColumnSelectProcessor()
			else if self.status is 'newAttributeValues':
				AttributeValueProcessor()




	def __init__(self, username, password):
		if CheckUserExists(username):
			if CheckUserPass(username, password):
				query = 'select tablename from %s where username = %s', userTable, username
				ret = Sql_Query(query)
				if ret[0]:
					self.tablename = ret[0]
				else:
					tablename = CreateUserTable(username)
				self.PopulateSessionTable()

	username = ''
	sessions = []
	tablename = ''

	currentSession = None

	def PopulateSessionTable(self):
		if self.tablename is '':
			return -1
		else:
			query = 'select id,status from %s', self.tablename
			ret = Sql_Query(query)
			sessionReturn = ret.fetchall()
			self.sessions = []
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


	def GetUserFrontPage(self):
		userInfo = """
		<div id="username">
		%s
		</div>
		""", self.username
		userStuff = '<div><div>'.join(userInfo).join('</div><div>').join(self.GetAccountManagementHTML()).join('</div></div>')
		
		return '<div>'.join(userStuff).join(self.SessionSelectHTML()).'</div>'


	def SessionSelectHTML(self):
		topString = '<div><input id="sessionSelect" type="select" name="sessionSelect">'
		if len(self.sessions) == 0:
			return returnString.join('</input></div>')

		rows = []
		for s in self.sessions:
			if len(s) not 2:
				#some bad format, exit
				return returnString.join('</input>')
			else:
				rows[] = '<option value ="%s">%s</option>', s[0], s[0]+','+s[1]

		continueButton = '<input type="button" name="continueSession"/>'
		deleteButton = buttons.join('<input type="button" name="deleteSession"/>')
		newButton = buttons.join('<input type="button" name="newSession"/>')

		returnString = topString.join(rows).join('</input></div><div>').join(continueButton).join(deleteButton).join(newButton).join('</div')
		return returnString


	def GetAccountManagementHTML(self):

		changeUsername = """
		<div>
		New Username: <input type="textline" name="newUsername"/>
		<br>
		<input type="button" name="changeUsername" value="Change Username"/>
		</div>
		"""
		changePassword = """
		<div>
		Current Password: <input type="textline" name="currentPassword"/>
		<br>
		New Password: <input type="textline" name="newPassword"/>
		<br>
		<input type="button" name="changePassword" value = "Change Password"/>
		</div>
		"""
		return '<div>'.join(changeUsername).join(changePassword).join('</div>')


	def SetUsername(username): 

	def ChangeUsername(newUsername):

	def ChangePassword(currentPassword, newPassword):


}


from bottle import route, install, template 
from bottle_sqlite import SQLitePlugin 

install(SQLitePlugin(dbfile='/tmp/test.db')) 
@route('/show/<post_id:int>')
def show(db, post_id): 
    c = db.execute('SELECT title, content FROM posts WHERE id = ?', (post_id,)) 
    row = c.fetchone() 
    return template('show_post', title=row['title'], text=row['content']) 


def Sql_Query(db, query):
	c = db.execute(query)
	if c is None:
		return -1
    row = c.fetchone()
    if row:
    	return row
    else:
    	return -1

def 


























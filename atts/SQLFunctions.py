import os
import pickle
from bottle import route, run, template


def CreateUserTable(username):
	query = 'create table %s (%s)', sessionTablePrefix+username, sessionTableStructure
	query = 'insert into %s (name) values (%s)', userSessions, username

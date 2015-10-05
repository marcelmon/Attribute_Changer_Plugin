#!/usr/bin/env python

from HTMLParser import HTMLParser
from htmldom import htmldom
import os


class valueChanger:
	"""docstring for valueChanger"""
	def __init__(self, arg):
		super(valueChanger, self).__init__()
		self.arg = arg
	
	def AddNewAttribute(attributeName, type, allowedValues):
		req = Sql_Query("select id from %s where name = %s", global attributeTable, attributeName)
		if not req:
			req = Sql_Query("alter table %s add column %s", global attributeTable, attributeName);
			if not req:
				return -1
			req = Sql_Query("alter table %s add values (%s) where", global tableValues, allowedValues)

	def AddNewAttributeValue(attributeId, value):
		req = Sql_Query_Array("select type,valueTable from %s where id = %s", global attributeTable, attributeId):
		if not req:
			return -1;
		if req[0] is 'checkboxgroup'|'radio'|'select':
			ret = Sql_Query('insert unique into %s name %s', req[1], value)
			return 1
		return -1



		req = Sql_Query("insert into %s value = %s", )
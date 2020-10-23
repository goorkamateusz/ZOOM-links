import json
from datetime import datetime

# todo dox
# todo spotkania ktore sa co tydzien
# todo Dodawanie spotkań zamiast tworzenia od nowa pliku

# Otwórz plik z zaproszeniami
file = open("../data/invitation-list.temp.json")

invitations = json.loads( file.read() )

# Klasa spotkania
class Lecturer :
	lect = ''					# imie naziwsko prowadzacego
	term = []					# lista spotkan

	# konstruktor
	def __init__( self, le ):
		self.lect = le
		self.term = []

			# do stringa
	def __str__( self ):
		return '{  "lect":"'+self.lect+'",\n   "term":[\n     ' + ",\n     ".join( map(str,self.term) ) + '\n    ],\n   "disc":""\n}'

	pass

# Klasa terminow
class Term :
	hour = ''				# godzina : minuta
	day = 0 				# dzien (pon=1)
	week = ''				# TN / TP

	# konstruktor
	def __init__( self, datestr ):
		date = datetime.strptime( datestr, "%Y-%m-%d %H:%M:%S.000000" )

		self.hour = date.strftime("%H:%M")

		self.day =  int( date.strftime("%w") )
		if self.day == 0 : self.day = 7

		if int(date.strftime("%W")) % 2 == 1:
			self.week = "N"
		else:
			self.week = "P"

	# do stringa
	def __str__( self ):
		return '{"hour":"' + self.hour + '" ,"day":"' + str(self.day) + '", "week":"' + self.week + '", "disc":""}'

	def __eq__( self, oth ):
		return self.hour == oth.hour and self.day == oth.day and self.week == oth.week

	pass

# Generuj listę kanałów discorda do uzupełnienia
discord = []
lect = []

for inv in invitations :

	if inv["lecturer"] not in lect :
		lect.append( inv["lecturer"] )

		l = Lecturer( inv["lecturer"] )
		l.term.append( Term( inv["date"]["date"] ) )

		discord.append( l )

		print( "-> ", inv["lecturer"] )
		print( "\n".join( map(str, l.term) ), " <- new lect" )

	else :
		for di in discord :
			if di.lect == inv["lecturer"] :
				term = Term( inv["date"]["date"] )
				print( term, "<- next term" )

				if term not in di.term :
					di.term.append( term )

				break

# Zapis do pliku
out = open("../discord-channel.json", "w")
out.write( "[\n" + ",\n".join( map(str, discord ) ) + "\n]" )
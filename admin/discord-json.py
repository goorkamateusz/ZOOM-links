# ---------------------------------------------------------
import json
from datetime import datetime

# todo spotkania ktore sa co tydzien

# ---------------------------------------------------------
# Klasa spotkania
class Lecturer :
	lect = ''					# imie naziwsko prowadzacego
	term = []					# lista spotkan
	disc = '' 					# webhook do kanalu

	# konstruktor
	def __init__( self, le, disc = '' ):
		self.term = []
		self.lect = le
		self.disc = disc

	# do stringa
	def __str__( self ):
		return '{  "lect":"'+self.lect+'",\n   "term":[\n     ' + ",\n     ".join( map(str,self.term) ) + '\n    ],\n   "disc":""\n}'

	# Czy rowne
	def __eq__( self, st ):
		return st == self.lect

	pass


# Klasa terminow
class Term :
	hour = ''					# godzina : minuta
	day  = 0 					# dzien (pon=1)
	week = ''					# TN / TP
	disc = '' 					# webhook do kanalu

	# konstruktor
	def __init__( self, *arg ):
		if len( arg ) == 1 :
			date = datetime.strptime( arg[0], "%Y-%m-%d %H:%M:%S.000000" )

			self.hour = date.strftime("%H:%M")

			self.day =  int( date.strftime("%w") )
			if self.day == 0 : self.day = 7

			if int(date.strftime("%W")) % 2 == 1: 		#hack zamienione by zgadzalo sie z kalendarzem
				self.week = "P"
			else:
				self.week = "N"
		else:
			self.hour = arg[0]
			self.day  = int(arg[1])
			self.week = arg[2]
			self.disc = arg[3]

	# do stringa
	def __str__( self ):
		return '{"hour":"' + self.hour + '" ,"day":"' + str(self.day) + '", "week":"' + self.week + '", "disc":""}'

	# czy rowne
	def __eq__( self, oth ):
		return (self.hour == oth.hour and self.day == oth.day and self.week == oth.week)

	pass

# ---------------------------------------------------------

# Otwórz plik z zaproszeniami
file = open("../data/invitation-list.json","r")
invitations = json.loads( file.read() )		# Tablica wszystkich zaproszeń
file.close()


# Otworz plik z kanałami
try:
	file = open("../discord-channel.json","r")
	discord_json = json.loads( file.read() )
	file.close()
except:
	discord_json = ""


# Przepisuje dane z JSON discorda do klas Lecturer
discord = []

for lect_json in discord_json :
	lect = Lecturer( lect_json["lect"], lect_json["disc"] )

	for term in lect_json["term"] :
		lect.term.append( Term( term["hour"], term["day"], term["week"], term["disc"] ) )

	discord.append( lect )


# Generuj listę kanałów discorda do uzupełnienia
for inv in invitations :

	if inv["lecturer"] not in discord :
		l = Lecturer( inv["lecturer"] )
		l.term.append( Term( inv["date"]["date"] ) )

		discord.append( l )

		print( "New lecturer: ", inv["lecturer"] )
		print( "\n".join( map(str, l.term) ), " <- new lect" )

	else :
		# Dodaje do odpowiedniego prowadzacego termin
		for di in discord :
			if di.lect == inv["lecturer"] :
				term = Term( inv["date"]["date"] )

				if term not in di.term :
					di.term.append( term )
					print( term, "<- next term" )

				break

# Zapis do pliku
out = open("../discord-channel.json", "w")
out.write( "[\n" + ",\n".join( map(str, discord ) ) + "\n]" )
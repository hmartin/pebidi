Pebidi

Enter your email to create your own bilingual dictionary!
Save words and do test on it.

Technical architecture:
Api: sf2 + mysql
Front: AngularJs + Bootsrap 3


micro todo:
worldwide stat*
Limit words for gw (mini 15 words)*
slug group*
pager wordlist*
repair personal stat
if no user on page who need user then redirect
Think security
Remove personal gw
tips dont add more 100w
use slug in link
add word to a group
Special page for got
optim with json in db
new count point system last 5 with ponderation
chrome extension in the cloud (0.5)
Did in Session for chrome extension (1d)


Functional test scenario:
Post email new user
Add words (exist and no-exist)
Execute suck no-exist

Security:
Pass user id
auto log if not secure user

Case:
Your pebidi
Pebidi of : (no edit) 
Group of: (no edit except it's yours)

UPDATE Dictionary SET slug = id;
INSERT INTO DictionaryWord (word_id, dictionary_id) SELECT  word_id, dictionary_id FROM DictionariesWord;

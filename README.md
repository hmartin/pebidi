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
Remove personal gw*
add slug in link*
add word to a group*
new count point system*
repair personal stat*
tips dont add more 20w
Special page for got
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

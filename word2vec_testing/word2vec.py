#script que processa texto nao estruturado e fornece como entrada para treinar
#o word2vec

import nltk, re, pprint
from nltk import punkt,word_tokenize
import codecs
import gensim

f = open("corpus.txt",'r') #corpus
d = codecs.open('corpus_processado.txt','w','utf-8')
text = f.read()
text = unicode(text,'utf-8','ignore')

stopwords = nltk.corpus.stopwords.words('portuguese')
stopwords.extend(['[',']','.',',',';',':','...','?','!'])

sent_tokenizer=nltk.data.load('tokenizers/punkt/portuguese.pickle')
text_tokenized = sent_tokenizer.tokenize(text)
input_word2vec = [[palavra.lower() for palavra in word_tokenize(sentenca) if palavra not in stopwords] for sentenca in text_tokenized] 

model = gensim.models.Word2Vec(input_word2vec, size=30, window=10, min_count=2, workers=10) #passando parametros
model.train(input_word2vec, total_examples=len(input_word2vec), epochs=10) #treinando o modelo

print model.wv['igreja'] #testando

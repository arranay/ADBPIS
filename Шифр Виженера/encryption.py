alphabet="абвгдеёжзийклмнопрстуфхцчшщъыьэюя"
signs=[" ",";",",",":","-","!","?", "\"", "."]

# Сдвигаем алфавит и генерируем словарь
def creating_dictionary(key):
    key=key.lower()
    dictionary = []
    for i in key:
        temporary_dictionary=alphabet[alphabet.index(i):alphabet.index("я")]+"я"+alphabet[alphabet.index("а"):alphabet.index(i)]
        dictionary.append(temporary_dictionary)
        print(temporary_dictionary)
    return dictionary

# Переводим введенную фразу в нижний регистр, удаляем пробелы и знаки препинания
def share_phrase(phrase, numberOfLetters):
    phrase=phrase.lower()
    for i in signs:
        phrase=phrase.replace(i, '')
    phrase=[phrase[i:i+numberOfLetters] for i in range(0, len(phrase), numberOfLetters)]
    return phrase

# Метод шифрования
def encryption(dictionary,word,numberOfLetters):
    j=0
    encrypted_word=""
    while j<numberOfLetters:
        for i in word:
            index=alphabet.index(i)
            encrypted_word+=dictionary[j][index]
            j+=1
        return encrypted_word

#  Метод дешифрования
def decode(dictionary,word,numberOfLetters):
    j=0
    encrypted_word=""
    while j<numberOfLetters:
        for i in word:
            index=dictionary[j].index(i)
            encrypted_word+=alphabet[index]
            j+=1
        return encrypted_word

# Форматирование строки
def formatting(phrase, source_phrase):
    index=0
    phrase_array=list(phrase)
    for i in source_phrase:
        for s in signs:
            if i==s:
                phrase_array.insert(index,s)
        index += 1
    phrase="".join(phrase_array)
    return phrase

phrase = input("Введите фразу: ")
source_phrase=phrase
key = input("Введите ключ шифрования: ")

print("\nСдвигаем словарь")
dictionary=creating_dictionary(key)

print("\nРазбиваем текст на группы")
phrase =share_phrase(phrase, len(key))
print(phrase)

choice = input("\nВыбирите действие:\n"
      "                     1: Зашифровать\n"
      "                     2: Дешифровать\n")

encrypted_keyword = []

if choice=="1":
    print("\nЗаменяем символы каждой группы на соответствующие символы из словаря")
    for i in phrase:
        encrypted_keyword.append(encryption(dictionary, i, len(key)))
    print(encrypted_keyword)
elif choice=="2":
    print("\nЗаменяем символы каждой группы на соответствующие символы из алфавита")
    for i in phrase:
        encrypted_keyword.append(decode(dictionary, i, len(key)))
    print(encrypted_keyword)
else:
    print("Вы ввели неверный символ (((")

print("\nВозвращаем текст к исходному форматированию")
phrase="".join(encrypted_keyword)
phrase=formatting(phrase, source_phrase)
print(phrase)


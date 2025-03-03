#include <iostream>
#include <fstream>
#include <string>

using namespace std;

typedef struct Person{
    string name;
    int age;
    string address;
}Person;

void savePersonToFile(const Person& person, const string& filename) {
    ofstream file(filename, ios::app);
    
    if (!file) {
        cerr << "Error opening file: " << filename << endl;
        return;
    }

    file << person.name << "\n";
    file << person.age << "\n";
    file << person.address << "\n";
    
    file.close();
}

int main(){

    Person person;
    person.name = "John";
    person.age = 25;
    person.address = "USA";
    savePersonToFile(person, "person.txt");

    return 0;
}

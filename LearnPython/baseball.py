import re
import sys, os

# quoted from course wiki: https://classes.engineering.wustl.edu/cse330/index.php?title=Python, 
# Command-Line Arguments

if len(sys.argv) < 2:
    sys.exit("Usage: %s requires at least one argument for input file name! " % sys.argv[0])

filename = sys.argv[1]

if not os.path.exists(filename):
    sys.exit(f"Error: File '{sys.argv[1]}' not found")



def mainCode(input):
    # Using dic to store names and numbers 
    names = {} # {name : avg}
    bats = {} # {name : batted times}
    hits = {} # {name : # of hits}

    # quoted from course wiki: https://classes.engineering.wustl.edu/cse330/index.php?title=Regular_Expressions Python

    # First check if the line is "playname batted # times with # hits and # runs"
    # player name should start with first name composed of Uppercase alphabetic folloing at least 1 lowercase letter
    # followed by a space and the last name, again, uppercase alphabetic and lowercase letter just like firstname
    # after name, followed by a space with "batted" space and a number, this number may be 0, 1, 2, 
    # or 11(double digits) so I using +
    
    # This is a line to grasp the meaningful line and group the name
    meaningful_regex = re.compile(r"([A-Z][A-Za-z]+ [A-Z][A-Za-z]+) batted (\d+) times with (\d+) hits and \d+ runs")
    # Get all the numbers, I'll use only when there's a meaningful line
    # number_regex = re.compile(r"\d+")

    # Edit on Feb 24, remove the number_regex, as we can group the regular expression and get the info.

    file = open(input, "r")
    for line in file:
        meaning = meaningful_regex.match(line)
        # if the line is a match
        if meaning is not None:
            # get the name from the group 1
            playername = meaning.group(1)
            # get all numbers
            '''# numbers = number_regex.findall(line)'''
            # we only wants the first and the second number, batted times and hits
            # group 2 and 3 in meaning
            bat = int(meaning.group(2))
            hit = int(meaning.group(3))         
            # if the dictionary has the key, then update it     
            if playername in bats:
                bats[playername] += bat
                hits[playername] += hit
            # if not contains this key, create one and assign values
            else:
                bats[playername] = bat
                hits[playername] = hit

    file.close()

    # quoted from course wiki: https://classes.engineering.wustl.edu/cse330/index.php?title=Python
    # Dictionary, iterate the dictionary
    # iterate the hits,
    for player, hits in hits.items():
        # set the key = player(name) and the value is the avg
        names[player] = hits / bats[player]
        #print(names[player])

    # merge two function into 1, perform sort and print inside the mainCode function
    sort = sorted(names.items(), key = lambda v: v[1], reverse=True)
    for name in sort:
        print(f"{name[0]} : {name[1]:.3f}")

    #return names

# A function to sort and print out the result
# def sortAndPrint(playerlist):
#     # quoted from course wiki: Python sorting, sortin by the value instead of key by specifing v[1], 
#     # and set the reverse = True to display in the descending order
#     sort = sorted(playerlist.items(), key = lambda v: v[1], reverse=True)
#     # round the number and print out the message
#     # quoted from course wiki, below is the example code
#     '''
#     An Example Python Script
#     In my mind, there's no better way to learn Python than to be immersed in a simple example script.

#     print("Hello World")

#     fruits = ["apple", "banana", "cherry", "date"]
#     for fruit in fruits:
#         print(f"I always love to eat a fresh {fruit}")

#     # Map the fruits list over to a new list containing the length of the fruit strings:
#     fruit_size = [len(fruit) for fruit in fruits]

#     avg_fruit_size = sum(fruit_size) / len(fruit_size)
#     print(f"The average fruit string length is {avg_fruit_size:.2f}.")

#     '''

#     for name in sort:
#         print(f"{name[0]} : {name[1]:.3f}")



if __name__ == "__main__":
    # simply call the mainCode function
    mainCode(filename)
#!/usr/bin/env sh
. "$(dirname -- "$0")/_/husky.sh"

# Cores
NC='\033[0m'
BBlue='\033[1;34m'
BRed='\033[1;31m'

# Function to show a spinner
spinner() {
  local indicators=("⠋" "⠙" "⠹" "⠸" "⠼" "⠴" "⠦" "⠧" "⠇" "⠏")
  local i=0
  while :; do
    printf "\r${indicators[i]} Running... "
    sleep 0.1
    i=$(( (i+1) % ${#indicators[@]} ))
  done
}

# Start the spinner in the background
spinner & SPINNER_PID=$!

REGEX_ISSUE_ID="[a-zA-Z0-9,\.\_\-]+-[0-9]+"
BRANCH_NAME=$(git symbolic-ref --short HEAD)
ISSUE_ID=$(echo "$BRANCH_NAME" | grep -o -E "$REGEX_ISSUE_ID")
COMMIT_MESSAGE=$(cat "$1")

if [ -z "$ISSUE_ID" ]; then
    echo -e "${BRed}Branch não está no padrão que deveria mestre... ${NC}"
    kill $SPINNER_PID
    exit 1
fi

# i.g. HEY-1: HEY-1: my feature
if [[ $COMMIT_MESSAGE == $ISSUE_ID* ]]; then
    kill $SPINNER_PID
    exit 0
fi

# Stop the spinner
kill $SPINNER_PID

echo "$ISSUE_ID: $COMMIT_MESSAGE" >$1

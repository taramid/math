#!/usr/bin/env bash
set -e

SLUG=$1
BASE_BRANCH=${2:-$(git branch --show-current)}

if [ -z "$SLUG" ]; then
    echo "Error: please specify a task/branch name."
    echo "Usage: ./wt.sh <slug> [base_branch]"
    echo "Example: ./wt.sh fix-auth"
    echo "Example: ./wt.sh fix-auth master"
    exit 1
fi

BRANCH="ai/$SLUG"
DIR="../$(basename "$PWD")-${SLUG}"

echo "==> Creating worktree: $DIR (branch: $BRANCH from $BASE_BRANCH)..."
git worktree add -b "$BRANCH" "$DIR" "$BASE_BRANCH"

if [ -f .env.local ]; then
    echo "==> Copying .env.local..."
    cp .env.local "$DIR/.env.local"
fi

cd "$DIR"

echo "==> Installing Composer dependencies..."
composer install --quiet

echo "==> Building Tailwind CSS..."
php bin/console tailwind:build

TASKS_DIR=".tasks"
mkdir -p "$TASKS_DIR"

TIMESTAMP=$(date +"%Y%m%d-%H%M%S")
TASK_FILE="${TASKS_DIR}/${TIMESTAMP}-${SLUG}.md"


if [ ! -f "$TASK_FILE" ]; then
    echo "==> Creating task file: $TASK_FILE"
    cat << 'TASK' > "$TASK_FILE"
# Task for AI Agent

## Context
take into account AGENTS.md
...

## To-Do
...

## Note
you're welcome to make small, atomic commits for each logical change with clean messages
...

TASK
fi

cat << SIC > sic.sh
#!/usr/bin/env bash
set -e

# Isolated Git identity for this session only
export GIT_AUTHOR_NAME="pi | qwen3.8 27b"
export GIT_AUTHOR_EMAIL="ai@local"

export GIT_COMMITTER_NAME="\$GIT_AUTHOR_NAME"
export GIT_COMMITTER_EMAIL="\$GIT_AUTHOR_EMAIL"

echo "==> Launching AI Agent..."
echo "==> Git Identity: \$GIT_AUTHOR_NAME <\$GIT_AUTHOR_EMAIL>"
echo "==> Task File: $TASK_FILE"
echo "=================================================="

# pi "$TASK_FILE"
# junie "$TASK_FILE"

SIC

chmod +x sic.sh

echo ""
echo "=================================================="
echo " Worktree successfully created!"
echo " Directory: $DIR"
echo " Branch:    $BRANCH"
echo "=================================================="
echo "Next steps:"
echo "  1. cd $DIR"
echo "  2. Explain what to do in $TASK_FILE"
echo "  3. Tweak and execute: ./sic.sh"
echo "=================================================="

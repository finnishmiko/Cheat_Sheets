# GIT

## Remove file from Git

... and from filesystem:
```sh
git rm filename.txt
```

... and keep it in filesystem:
```sh
git rm --cached filename.txt
```

## Ignore local changes

```sh
git checkout -f
```

## Remove untracked files

```sh
git clean -fdx
# where
# -f = force
# -d = directories
# -x = ignored files
```

## Remove folder with large files from accidentally pushed to Git repository. 

```PowerShell
# check size
git count-objects -vH

# Iterate through all references on current branch (--tree-filter), running the command in quotes. Delete useless commits recursively (--prune-empty).
git filter-branch --tree-filter "rm -rf ./path/to/folder" --prune-empty HEAD

# Delete the reference to that old branch.
git for-each-ref --format="%(refname)" refs/original/ | foreach-object -process { git update-ref -d $_ }

# clean
git gc

# push with force
git push origin master --force

# Note that others using the repository need to clone it again.
# Also note that this solution reduces local Git size only after the repository is cloned again.
git clone <repository>

# check size
git count-objects -vH

```

## Get only changed files from repository to local zip file

```bash
# Note: this works only on linux based shell
git archive --output=../latest_changes.zip HEAD $(git diff --name-only HEAD HEAD^1) 
```

## Associate username and email to Git:
```sh
git config --global user.name "My Name"
git config --global user.email "myemail@address.com"
```

Check name and email:
```sh
git config user.name
git config user.email
```

End of line settings:

```sh
git config --global core.autocrlf true
```

## New Github repository - option 1
* Create new repository to Github with README.md and .gitignore files
* Clone that repository to your computer
```
git clone <path to github>
```
* Edit README.md and .gitignore as needed
* Copy your files to that folder and add them to Git
```sh
git add -A
git commit -m "initial commit"
git push origin master
```

## New Github repository - option 2
* Create new repository to Github without README.md and .gitignore files
* In you computer go to project folder and initialize new Git repository
```
git init
```
* Add your program files to Git
```sh
git add .
git commit -m "First commit"
```
* From Github copy your repository URL and add it as a remote
```sh
git remote add origin <repository URL>
```
* And push your changes to Github:
```sh
git push origin master
```


## Branch
```sh
git branch # Check which branch you are working
git branch new_branch
git checkout branch_name # Switch to the new branch

# Then git add and commit as usual
git push origin your_branch_name

git merge branch_name # First switch to master branch so new branch changes will be made to master. This works when there is no changes in master.

git branch -d branch_name # will delete branch

```

Get the branch from remote:


```sh
git fetch origin # Find out the name of the remote branch
git checkout --track origin/branch_name
```
or if the branch name is not visible with above commands:
```sh
git fetch origin # Find out the name of the remote branch
git branch -v -a # See all the branches available for checkout
git checkout -b branch_name origin/branch_name
```


Update development branch from master branch (dev branch is checked out).

```sh
git fetch
git rebase origin/master

# If there is error: Failed to merge in the changes.
# Then resolve conflicts and add files with
git add file_name

# and continue with
git rebase --continue

```

Delete local branch

```sh
git branch -d branch_name
```


## Local modifications prevent `git pull origin master`
* Git is protecting you from losing the local modifications
```sh
error: Your local changes to the following files would be overwritten by merge:
        <filename>
Please commit your changes or stash them before you merge.
Aborting
```

[Options](https://stackoverflow.com/questions/15745045/how-do-i-resolve-git-saying-commit-your-changes-or-stash-them-before-you-can-me):

1. Commit the change using
`git commit -m "My message"`

2. Stash it to a stack with `git stash`. Then do the merge and  pop your changes back in reverse order:
`git stash pop`

3. Discard all local changes using `git reset --hard` or discard local changes for a specific file using `git checkout filename`


## Save Git log history to a text file
```sh
git --no-pager log > log.txt
```

```sh
# Display hash + comment in one line
git log --pretty=oneline

# Display changed filenames
git log --name-only

# Show last n commits
git log -n 5
```

## Detached HEAD state
- Switch to specific commit in Git history:
`git checkout [commit hash]`
- This is just for viewing history at a certain point in time
- Any commits in this mode won't be added to a branch. But new branch can be made from this commit with: `git checkout -b [new branch name]`.
- Return to the branch you came from with:
`git checkout -`
- Or return to master branch with `git checkout master`


## Set Git-server in uSD-card in local computer:
* Go to uSD-drive (f.ex. D:/) and create a repository folder and in it new project repository:
```sh
mkdir newrepo.git
git init --bare # use .git ending and --bare
```
* If this repository is needed to be cloned over IP then rename /hooks/post-update.sample to post-update and run
```sh
git update-server-info [--force] # Without this cloning does not work to another computer over IP
```
* Go to C:drive project folder and
```sh
git init
git remote add origin d:/repofolder/newrepo.git # origin can be called something else also
```
* Then as usually add, commit and push new files


## Create remote repository to Virtual Machine

* Create new username:
```sh
sudo adduser username
```
* Create new group called git-users and change its permissions:
```sh
sudo addgroup git-users
sudo usermod -a -G git-users username
groups username
sudo chown -R root:git-users /srv/git/
```

[Repair permissions](https://stackoverflow.com/questions/6448242/git-push-error-insufficient-permission-for-adding-an-object-to-repository-datab#6448326)
```sh
git config core.sharedRepository
# If it's not group or true or 1 or some mask, try running:

git config core.sharedRepository group

# and then run
cd /path/to/repo.git
chgrp -R git-users .
chmod -R g+rwX .
find . -type d -exec chmod g+s '{}' +

# or all in once with sudo
sudo chgrp -R git-users . && sudo chmod -R g+rwX . && sudo find . -type d -exec chmod g+s '{}' +
```

Add git repository as a remote or clone existing repository:

```sh
git remote add origin <username>@<path to server>:/srv/git/test.git

git clone <username>@<path to server>:/srv/git/test.git
```

## Push changes directly to a web site


[Followed this guide to ](http://toroid.org/git-website-howto)
"__push__ into a __remote__ repository that has a detached work tree, and a __post-receive__ hook that runs `git checkout -f`."


1. Create local repository that is used for development
2. Create remote repository folder `project.git` and run `git init --bare` in that folder
3. Create another folder that will have latest tree into the webserver's DocumentRoot: `mkdir /var/www/project`
4. Create Git hook that will run when new commits are reveived. To `project.git/hooks` folder create a file `post-receive` with content:

```sh
#!/bin/sh
GIT_WORK_TREE=/var/www/project git checkout -f
```

and modify its permissions:
`chmod +x hooks/post-receive`.

5. To development repository add Git remote and push master branch there:
```sh
$ git remote add web <username>@<server>:<path>/project.git
$ git push web +master:refs/heads/master
```

6. Push following commits normally and web page will be updated.

## Another _post-receive_ hook example with bash

This time only production branch gets deployed. Also the project is installed and build before publishing to web site.


```bash
#!/bin/bash
BRANCH="production"
while read oldrev newrev ref
do
    # only checking out the wanted branch
    if [[ $ref = refs/heads/"$BRANCH" ]];
    then
        echo "Ref $ref received. Deploying branch to production..."
        git --work-tree=/home/user/project/ --git-dir=/home/user/project.git/ checkout "$BRANCH" -f
    else
        echo "Ref $ref successfully received. Doing nothing: only the ${BRANCH} branch can be deployed to this server."
    fi
done
echo "Change to project directory and install"
cd /home/user/project
npm i
if [[ $? -eq 0 ]];
then
    echo "Installing Ok"
else
    echo "Installing Failed"
    exit 1
fi

echo "Building"
npm run build
if [[ $? -eq 0 ]];
then
    echo "Building Ok"
else
    echo "Building Failed"
    exit 1
fi

echo "Create backup of current web site"
mkdir /home/user/project/backup/`date +%F`
cp -r /var/www/html/project/* /home/user/project/backup/`date +%F`/
if [[ $? -eq 0 ]];
then
    echo "Backup Ok"
else
    echo "Backup Failed"
    exit 1
fi

echo "Updating web UI"
sudo rm -rf /var/www/html/project/*
sudo cp -r /home/user/project/build/* /var/www/html/project/
if [[ $? -eq 0 ]];
then
    echo "Deployment Ok"
else
    echo "Deployment failed. Copy old project back to server"
    cp -r /home/user/project/backup/`date +%F`/* /var/www/html/project/
    exit 1
fi
```

# Overall review

The requirements are lacking (probably on purpose) and I didn’t receive the
evaluation criteria even after explicitly asking. They claimed that there are no
criteria and they just want to see my approach.

Well, let me break the truth to you: that’s a recruitment task, it is here for
a reason, I might fail it. The tech recruiter will look at it and there will be
things they will expect (and probably missing from my solution), or elements
that they disagree with which will raise red flags. The thing is, I have no way
of knowing them beforehand, so I am attempting this completely blind.

This leads me to believe that this process is biased and probably a waste of
my time, as there is high chance I will be disqualified on a whim.

The upside is that it focuses on domain logic (albeit not interesting, IMO) and
unit tests, which rocks my boat.

# Requirements

## Locking

The requirements make little sense here, and are poorly stated. In the same sentence:

* locked assessments cannot be locked
* but suspended can be changed to withdrawn

One contradicts the other. I get that you’re aiming at a state machine (you won’t get it,
btw, I don’t believe it’s the only solution, especially since the requirements do not
clearly reflect that), but I don’t like to be thrown a curveball.

And there is absolutely no mentioned of how those side effects should be observed, so
all those requirements have no point.

## If Client has an active assessment

Not clear what does „active assessment” mean. Unlocked? Maybe, maybe not. I ignored this requirement.
Implementing it would require one line, and it does not pose any challenges as such.

# Design choices

## AssessmentFactory

There were two approaches to consider:

* Using a separate factory with a „clear” entity accepting any client/supervisor/standard trio
* A factory method on the `Assessment` entity with a private constructor

The logic would be the same, but the latter would bring more guarantees about the
`Assessment` entity state: one just couldn’t be created without passing the requirements.
But it was unclear if the mentioned rules are constant across the application. If new flows
were ever to be added, new factories or factory methods would be required. This would
complicate the entity as well, and I’m feeling that it’s in the breaking SRP territory.

Long story short, I decided to go with a separate factory, but in practice, regardless
of what I would choose, I would raise the issue in review, like here.

## Repositories

There is a delicate balance to be made:

* Should we couple or decouple?

In this case, we could build entities (eg. `Supervisor`) with their relations embedded
(`Authority`). Without more context, there is no way of making the correct choice.
The authorities might come from a completely different system for all I know. And since
it was easier to test this way, I decided to use a repository to get them.

As usual, this depends, and having `Supervisor::authorities()` could be a completely valid
approach too.

## `when` factory methods on exceptions

It’s a code style choice, you might like it, you might not.

The idea is that the exceptions serves both as an assertion, and throws itself if
the exceptional condition is encountered.

## Clock

It was not really required, too bad :(

## Exceptions

It is unclear if failing the mentioned requirements is an exceptional case. An exception
safety might be considered instead.

# Unit tests

They use the `assert/arrange/act` structure, here implemented in gherkin-like syntax: given/when/then.
The code style allows space in method names for readability. It’s a stylistic choice, you might like it,
you might not (make sure your IDE does not display &nbsp; as a special token, but use a space instead),
but ultimately CS is for linters to check, let’s not talk about it.

The Mother pattern allows to reduce boilerplate in tests, and to focus on what’s important.
---
name: ceetea.uk
alias: ceetea-uk
url: https://ceetea.uk
rssUrl: https://ceetea.uk/index.xml
status: Active
codeUrl: https://github.com/johnnoel/ceetea.uk
---

ceetea.uk started as an experiment to see how quickly I could get a blog up and running using free hosting (GitHub) and a static site generator ([Hugo](https://gohugo.io/)). Turns out less than an an afternoon.

Envisioned as writing experiment: using illustrations from some of the many [Twitter artists I follow](https://twitter.com/ceetea_/likes) as seeds for short stories, it has  changed to be a space for more general, usually speculative, fiction writing when fleeting glimpses of inspiration strike.

As with many of the static site generators that I've tried out, Hugo is fine for "the basics" - plain content pages and inflexible taxonomies. The question is what compromises you are willing to make to your ideal to fit with what your generator of choice provides.For ceetea.uk I was less interested in the specifics of what Hugo provided and more interested in how easy getting up and running was e.g. did I have to download [Go](https://golang.org/) (no) or could I use something pre-built (yes, [a Docker image](https://hub.docker.com/r/klakegg/hugo/)) and what was the out-of-the-box theming like (the [theme I use](https://github.com/EmielH/tale-hugo/) is nice).

Setting up GitHub pages was straightforward with [decent documentation](https://docs.github.com/en/pages) and it provides a TLS certificate for custom domains (a necessity), although there is some faffery involved when generating the site as it has to go [into a different repo](https://github.com/johnnoel/johnnoel.github.io) though this is specific to my setup and if I slummed it with a single repo this wouldn't be an issue. There was also an issue with the TLS certificate renewal as I'd put the domain behind Cloudflare's DNS proxy which stopped GitHub's Let's Encrypt integration from working; as the site isn't hosted by me, proxying the DNS is less of a concern.

Though marked as active, due to time constraints I rarely post to ceetea.uk.

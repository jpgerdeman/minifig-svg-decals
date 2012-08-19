# @TODO vestigial code from PHP. Still necessary?
class GitPath
	def initialize (path)
		@path = path
	end

	def isSvg()
		return @path.include?'.svg'
	end
end